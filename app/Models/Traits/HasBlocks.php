<?php
namespace App\Models\Traits;

use Auth;
use App\Models\Core\Content\Block;

trait HasBlocks {

    public function blocks()
    {
        return $this->morphMany(Block::class, 'has_blocks');
    }

    public function saveBlock($blockData, $parentId)
    {
        $content = isset($blockData->content) ? $blockData->content : null;
        $block = $this->blocks()->updateOrCreate(
            ['unique_id' => $blockData->uniqueId],
            ['type' => $blockData->type, 'settings' => $blockData->settings, 'content' => $content, 'parent_id' => $parentId, 'order' => $blockData->order, 'user_id' => auth()->user()->id]
        );

        return $block;
    }
}
