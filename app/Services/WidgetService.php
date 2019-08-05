<?php

namespace App\Services;

use App\Models\Core\Design\ThemeSetting;

use App\Models\Core\Design\Widget;
use App\Models\Core\Content\Block;

class WidgetService
{

    /**
     * Save Widget and the attached widget blocks
     */
    public function save($request)
    {
        // Remove deleted widgets
        foreach ($request->removedItems as $key => $itemId) {
            $block = Block::where('unique_id', $itemId)->first();
            $block ? $block->delete() : null;
        }

        $widget = Widget::firstOrNew(['id' => $request->id]);
        $widget->title = $request->title;
        $widget->theme_area = $request->themeArea;
        $widget->layout = $request->layout;
        $widget->filter_mode = $request->filterMode;
        $widget->filter_data = $request->filterData;
        $widget->save();

        // save Widget Blocks
        foreach($request->widgetsData as $key => $blockData) {
            $blockData = (object) $blockData;
            $this->updateOrCreateBlock($widget, $blockData);
        }
        $widget = $widget->fresh();
        $widget->load('blocks');
        return $widget;
    }

    protected function updateOrCreateBlock($widget, $blockData, $parentId = null)
    {
        $blockData = (object) $blockData;
        $block = $widget->saveBlock($blockData, $parentId);

        // save child blocks recursivly
        if(isset($blockData->subItems)) {
            for ($i=0; $i < count($blockData->subItems); $i++) {
                $this->updateOrCreateBlock($widget, $blockData->subItems[$i], $block->unique_id);
            }
        }
    }

    public function isVisible($widget, $contentType, $contentId = null) 
    {
        $filterMode = $widget->filter_mode;

        if($filterMode == 'all')
            return true;
        
        $filterData = $widget->filter_data;

        $visible = array_key_exists($contentType, $filterData);

        if($visible) {
            $contentIds = $filterData[$contentType];
            if(empty($contentIds)) {
                return true;
            } else {
                return in_array($contentId, $contentIds);
            }
        }

        return false;
    }

}