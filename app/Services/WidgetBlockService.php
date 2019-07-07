<?php

namespace App\Services;

use App\Models\Core\Design\ThemeSetting;
use App\Models\Core\Design\WidgetBlock;

class WidgetBlockService
{
    static public function save($widget_id, $blockData, $parentId)
    {
        $block = WidgetBlock::where('unique_id', $blockData->uniqueId)->where('widget_id', $widget_id)->first();
        // $widget = WidgetBlock::firstOrNew(['unique_id' => $blockData->uniqueId]);

        if($block) {
            $block->widget_id = $widget_id;

            $block->order = $blockData->order;
            $block->unique_id = $blockData->uniqueId;
            $block->parent_id = $parentId;
            $block->user_id = 1;

            if(isset($blockData->content)) {
                if(is_array($blockData->content))
                    $block->content = json_encode($blockData->content);
                else
                    $block->content = $blockData->content;
            }

            $block->update();
        } else {
            $block = new WidgetBlock();
            $block->widget_id = $widget_id;
            $block->type = $blockData->type;
            $block->order = $blockData->order;
            $block->unique_id = $blockData->uniqueId;
            $block->parent_id = $parentId;

            $block->user_id = 1;

            if(isset($blockData->content)) {
                if(is_array($blockData->content))
                    $block->content = json_encode($blockData->content);
                else
                    $block->content = $blockData->content;
            }

            $block->save();
        }

        return $block;
    }
}