<?php

namespace App\Services;

use App\Models\Core\Design\ThemeSetting;

use App\Models\Core\Design\Widget;
use App\Models\Core\Design\WidgetBlock;

use App\Services\WidgetBlockService;

class WidgetService
{

    /**
     * Save Widget and the attached widget blocks
     */
    public function save($request)
    {
        // Remove user deleted widgets first
        foreach ($request->removedItems as $key => $itemId) {
            $widgetBlock = WidgetBlock::where('unique_id', $itemId)->first();
            $widgetBlock ? $widgetBlock->delete() : null;
        }

        // First or New Widget
        $widget = Widget::firstOrNew(['id' => $request->id]);
        $widget->title = $request->title;
        $widget->theme_area = $request->themeArea;
        $widget->layout = $request->layout;
        $widget->filter_mode = $request->filterMode;
        $widget->filter_data = $request->filterData;
        $widget->save();

        // save Widget Blocks
        foreach($request->widgetsData as $key => $widgetData) {
            $widgetData = (object) $widgetData;
            $this->saveWidgetBlock($widget->id, $widgetData);
        }
        $widget = $widget->fresh();
        $widget->load('blocks');
        return $widget;
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

    /** 
     * Recursivly save all nested widget blocks 
     * */
    private function saveWidgetBlock($widgetId, $widgetData, $parentId = null)
    {
        $widgetBlockService = new WidgetBlockService();
        $widgetData = (object) $widgetData;

        // create or update the widget in database
        $widgetBlock = $widgetBlockService->save($widgetId, $widgetData, $parentId);

        // remove settings
        foreach ($widgetBlock->getSettings() as $baseKey => $value) {
            if(!array_key_exists($baseKey, $widgetData->settings)) {
                $widgetBlock->removeSetting($widgetBlock->type, $baseKey);
            }
        }

        // only save settings that have been customized by user
        foreach ($widgetData->settings as $key => $setting) {
            $widgetBlock->setSetting($key, $setting['value'], $setting['type'], $widgetData->type);
        }

        // process sub widgets recursivly
        if(isset($widgetData->subItems)) {
            for ($i=0; $i < count($widgetData->subItems); $i++) {
                $this->saveWidgetBlock($widgetId, $widgetData->subItems[$i], $widgetBlock->unique_id);
            }
        }
    }

}