@foreach($renderData->subBlocksIds as $key => $blockId)
    @php
        $renderData->block = $renderData->allBlocks[$blockId];
        $renderData->key = $key;
        $renderData->subBlocksIds = $renderData->block->subItems;
        $blockType = $renderData->allBlocks[$blockId]->type;
    @endphp

    @component('content.blocks.'.lcfirst($blockType).'.block', [
        'renderData' => $renderData,
        'parentSettings' => isset($parentSettings) ? $parentSettings : null
    ])@endcomponent
@endforeach
