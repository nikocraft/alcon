    @foreach($rootBlocksIds as $blockId)
        @php
            $renderData = array();
            $block = $allBlocks[$blockId];

            $renderData['block'] = $block;
            $renderData['subBlocksIds'] = $block->subItems;
            $renderData['allBlocks'] = $allBlocks;
            $renderData['blockRenderType'] = "blocks";

            $renderData = (object) $renderData;
        @endphp

        @if(view()->exists('content.blocks.'.lcfirst($block->type).'.block'))
            @component('content.blocks.'.lcfirst($block->type).'.block', [
                'renderData' => $renderData
            ])@endcomponent
        @else
            @component('content.third-party-blocks.'.lcfirst($block->type).'.block', [
                'renderData' => $renderData
            ])@endcomponent
        @endif
    @endforeach
