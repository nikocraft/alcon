<?php

namespace App\Models\Core\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\SchemalessAttributes;

use App\Models\Core\Settings\HasSettings;
use Auth;

class ContentBlock extends Model
{
    use HasSettings;

    protected $table = "content_blocks";

    public $casts = [
        'extra_attributes' => 'array',
    ];

    public function getExtraAttributesAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'extra_attributes');
    }

    public function scopeWithExtraAttributes(): Builder
    {
        return SchemalessAttributes::scopeWithSchemalessAttributes('extra_attributes');
    }

    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id', 'id');
    }

    public function subBlocks()
    {
        return $this->hasMany(self::class, 'parent_id', 'unique_id');
    }

    public static function boot()
    {
       parent::boot();

       static::deleting(function ($model) {
           $model->settings()->delete();
           $model->settings()->sync([]);
           foreach ($model->subBlocks as $key => $subBlock) {
               $subBlock->delete();
               self::recursiveDelete($subBlock);
           }
       });
    }

    // return block settings with defaults...
    public function getSettings()
    {
        $settings = array();
        foreach ($this->extra_attributes->all() as $key => $setting) {
            $settings[$key] = $setting['value'];
        }

        return (object)$settings;
    }

    static public function set($content_id, $blockData, $parentId)
    {
        $block = ContentBlock::where('unique_id', $blockData->uniqueId)->where('content_id', $content_id)->first();

        if($block) {
            $block->content_id = $content_id;

            $block->order = $blockData->order;
            $block->unique_id = $blockData->uniqueId;
            $block->parent_id = $parentId;
            $block->extra_attributes = $blockData->settings;
            $block->user_id = Auth::check() ? Auth::user()->id : 1;

            if(isset($blockData->content)) {
                if(is_array($blockData->content))
                    $block->content = json_encode($blockData->content);
                else
                    $block->content = $blockData->content;
            }

            if(isset($blockData->templateBlockId)) {
                $block->tblock_id = $blockData->templateBlockId;
            }

            $block->update();
        } else {
            $block = new ContentBlock();
            $block->content_id = $content_id;
            $block->type = $blockData->type;
            $block->order = $blockData->order;
            $block->unique_id = $blockData->uniqueId;
            $block->parent_id = $parentId;
            $block->extra_attributes = $blockData->settings;

            $block->user_id = Auth::check() ? Auth::user()->id : 1;

            if(isset($blockData->content)) {
                if(is_array($blockData->content))
                    $block->content = json_encode($blockData->content);
                else
                    $block->content = $blockData->content;
            }

            if(isset($blockData->templateBlockId)) {
                $block->tblock_id = $blockData->templateBlockId;
            }

            $block->save();
        }

        return $block;
    }

    public static function recursiveDelete($subBlock) {
        foreach ($subBlock->subBlocks as $key => $model) {
            $model->delete();
            self::recursiveDelete($model);
        }
    }
}
