    @php
        $renderData = array();
        $block = $widgets[$widgetId];

        $renderData['block'] = $block;
        $renderData['subBlocksIds'] = $block->subItems;
        $renderData['allBlocks'] = $widgets;
        $renderData['blockRenderType'] = "widgets";

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