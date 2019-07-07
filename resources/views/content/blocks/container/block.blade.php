@php
    $block = $renderData->block;
    $settings = $block->getSettings();
    
    $subBlocksIds = $renderData->subBlocksIds;
    $allBlocks = $renderData->allBlocks;

    $containerClass = "container";
    if($settings->containerType == 'fullwidth')
        $containerClass = "container-fullwidth";
@endphp

@includeIf('content.blocks.container.css')

<div id="{{ $settings->blockRef }}" class="{{$containerClass}} container-{{ $block->unique_id }} {{ $settings->customClass }}" @if($settings->onClick == 'open-link')onclick="window.open('{{ $settings->link }}', '{{ $settings->target }}');"@endif>
    @component('content.render.blocks', [
        'renderData' => $renderData,
        'parentSettings' => $settings
    ])@endcomponent
</div>
