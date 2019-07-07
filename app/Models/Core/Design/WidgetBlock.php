<?php

namespace App\Models\Core\Design;

use Illuminate\Database\Eloquent\Model;

use App\Models\Core\Settings\HasSettings;
use Auth;

class WidgetBlock extends Model
{
    use HasSettings;

    protected $table = "widget_blocks";

    public static function boot()
    {
       parent::boot();

       static::deleting(function ($model) {
            $model->settings()->delete();
            $model->settings()->sync([]);
            // foreach ($model->subBlocks as $key => $subBlock) {
            //    $subBlock->delete();
            //    self::recursiveDelete($subBlock);
            // }
       });
    }

    public static function recursiveDelete($subBlock) {
        foreach ($subBlock->subBlocks as $key => $model) {
            $model->delete();
            self::recursiveDelete($model);
        }
    }

    public function widget()
    {
        return $this->belongsTo(Widget::class, 'widget_id', 'id');
    }

    public function subWidgets()
    {
        return $this->hasMany(self::class, 'parent_id', 'unique_id');
    }

    public function getSettings()
    {
        $settings = array();
        if (isset($this->type)) {
            $componentPaths = [
                'App\\Models\\Core\\Content\\Block\\' . studly_case($this->type) . 'Block',
                'App\\Models\\Core\\Content\\ThirdPartyBlocks\\' . studly_case($this->type) . 'Block'
            ];

            foreach ($componentPaths as $path) {
                if (!class_exists($path)) continue;
                $block = new $path;
            }

            if (isset($block) && method_exists($block, 'getDefaults')) {
                $settings = $block->getDefaults();
            }
        }

        foreach ($this->settings as $key => $setting) {
            $settings[$setting['key']] = $setting['value'];
        }

        return (object)$settings;
    }
}
