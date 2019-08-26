@php
    $block = $renderData->block;
    $settings = $block->settings;
    $subBlocksIds = $renderData->subBlocksIds;
    $allBlocks = $renderData->allBlocks;

    $parentDisplay = isset($renderData->display) ? $renderData->display : 'block';

    $containerClass = "section";
@endphp

@includeIf('content.blocks.section.css')

<div id="{{ $settings->get('blockRef') }}" class="{{ $containerClass }} section-{{ $block->unique_id }} {{ $settings->get('customClass') }}" @if($settings->get('onClick') == 'open-link')onclick="window.open('{{ $settings->get('link') }}', '{{ $settings->get('target') }}');"@endif>
    @php
        $renderData = array();
        $renderData['subBlocksIds'] = $subBlocksIds;
        $renderData['allBlocks'] = $allBlocks;
        $renderData['display'] = $settings->get('display');
        $renderData['flexDirection'] = $settings->get('flexDirection');
        $renderData = (object) $renderData;
    @endphp

    @component('content.render.blocks', [
        'renderData' => $renderData,
        'parentSettings' => $settings
    ])@endcomponent
</div>
