@php
    $block = $renderData->block;
    $settings = $block->getSettings();
    $subBlocksIds = $renderData->subBlocksIds;
    $allBlocks = $renderData->allBlocks;

    $parentDisplay = isset($renderData->display) ? $renderData->display : 'block';

    $containerClass = "section";
@endphp

@includeIf('content.blocks.section.css')

<div id="{{ $settings->blockRef }}" class="{{$containerClass}} section-{{ $block->unique_id }} {{ $settings->customClass }}" @if($settings->onClick == 'open-link')onclick="window.open('{{ $settings->link }}', '{{ $settings->target }}');"@endif>
    @php
        $renderData = array();
        $renderData['subBlocksIds'] = $subBlocksIds;
        $renderData['allBlocks'] = $allBlocks;
        $renderData['display'] = $settings->display;
        $renderData['flexDirection'] = $settings->flexDirection;
        $renderData = (object) $renderData;
    @endphp

    @component('content.render.blocks', [
        'renderData' => $renderData,
        'parentSettings' => $settings
    ])@endcomponent
</div>
