<?php
namespace App\Models\Traits;

use Auth;
use App\Models\Core\Content\Block;

trait HasWidgets {

    public function widgets()
    {
        return $this->morphMany(Block::class, 'has_blocks');
    }

    public function saveWidget($widgetData, $parentId)
    {
        $content = isset($widgetData->content) ? $widgetData->content : null;
        $widget = $this->widgets()->updateOrCreate(
            ['unique_id' => $widgetData->uniqueId],
            ['type' => $widgetData->type, 'settings' => $widgetData->settings, 'content' => $content, 'parent_id' => $parentId, 'order' => $widgetData->order, 'user_id' => auth()->user()->id]
        );

        return $widget;
    }
}
