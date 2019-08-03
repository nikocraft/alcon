<?php

namespace App\Models\Core\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\SchemalessAttributes;

use Auth;

class ContentBlock extends Model
{
    protected $table = "content_blocks";

    public $casts = [
        'settings' => 'array',
    ];

    public function getSettingsAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'settings');
    }

    public function scopeWithSettings(): Builder
    {
        return SchemalessAttributes::scopeWithSchemalessAttributes('settings');
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
           foreach ($model->subBlocks as $key => $subBlock) {
               $subBlock->delete();
               self::recursiveDelete($subBlock);
           }
       });
    }

    // return block settings as object
    public function getSettings()
    {
        return (object) $this->settings->all();
    }

    static public function set($content_id, $blockData, $parentId)
    {
        $block = ContentBlock::where('unique_id', $blockData->uniqueId)->where('content_id', $content_id)->first();

        if($block) {
            $block->content_id = $content_id;

            $block->order = $blockData->order;
            $block->unique_id = $blockData->uniqueId;
            $block->parent_id = $parentId;
            $block->settings = $blockData->settings;
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
            $block->settings = $blockData->settings;

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
