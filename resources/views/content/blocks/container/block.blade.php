@php
    $block = $renderData->block;
    $settings = $block->settings;
    
    $subBlocksIds = $renderData->subBlocksIds;
    $allBlocks = $renderData->allBlocks;

    $containerClass = "container";
    if($settings->get('containerType') == 'fullwidth')
        $containerClass = "container-fullwidth";
@endphp

@includeIf('content.blocks.container.css')

<div id="{{ $settings->get('blockRef') }}" class="{{$containerClass}} container-{{ $block->unique_id }} {{ $settings->get('customClass') }}" @if($settings->get('onClick') == 'open-link')onclick="window.open('{{ $settings->get('link') }}', '{{ $settings->get('target') }}');"@endif>
    @component('content.render.blocks', [
        'renderData' => $renderData,
        'parentSettings' => $settings
    ])@endcomponent
</div>
