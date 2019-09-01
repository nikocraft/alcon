<?php

namespace App\Services;

use App\Models\Core\Design\ThemeSetting;

use App\Models\Core\Design\WidgetGroup;
use App\Models\Core\Content\Block;

class WidgetService
{
    /**
     * Save WidgetGroup and widget blocks
     * 
     * @param request
     */
    public function save($request)
    {
        // Remove deleted widgets
        foreach ($request->removedItems as $key => $itemId) {
            $block = Block::where('unique_id', $itemId)->first();
            $block ? $block->delete() : null;
        }

        $widgetGroup = WidgetGroup::firstOrNew(['id' => $request->id]);
        $widgetGroup->title = $request->title;
        $widgetGroup->location = $request->location;
        $widgetGroup->layout = $request->layout;
        $widgetGroup->filter_mode = $request->filterMode;
        $widgetGroup->filter_data = $request->filterData;
        $widgetGroup->save();

        // save Widget Blocks
        foreach($request->widgetsData as $key => $widgetData) {
            $widgetData = (object) $widgetData;
            $this->updateOrCreateWidget($widgetGroup, $widgetData);
        }
        $widgetGroup = $widgetGroup->fresh();
        $widgetGroup->load('widgets');
        return $widgetGroup;
    }

    /**
     * Update or Create Widget and attach to widget group, this is recursive function since widget blocks can be nested
     * 
     * @param widgetGroup widget group to attach widget blocks to
     * @param block to attach to the widget group
     * @param parentId current widget block parent id
     */
    protected function updateOrCreateWidget($widgetGroup, $widgetData, $parentId = null)
    {
        $widgetData = (object) $widgetData;
        $widget = $widgetGroup->saveWidget($widgetData, $parentId);

        // save child blocks recursivly
        if(isset($widgetData->subItems)) {
            for ($i=0; $i < count($widgetData->subItems); $i++) {
                $this->updateOrCreateWidget($widgetGroup, $widgetData->subItems[$i], $widget->unique_id);
            }
        }
    }

    /**
     * Check if a specific widget group is allowed to render on a specific content type & content id
     * 
     * @param widgetGroup
     * @param contentType
     * @param contentId
     */
    public function isVisible($widgetGroup, $contentType, $contentId = null) 
    {
        $filterMode = $widgetGroup->filter_mode;

        if($filterMode == 'all')
            return true;
        
        $filterData = $widgetGroup->filter_data;

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