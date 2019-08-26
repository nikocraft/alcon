@php
    $block = $renderData->block;
    $settings = $block->settings;
@endphp

@includeIf('content.blocks.columns.css')

<div class="columns-block columns-{{ $block->unique_id }} {{ $settings->get('customClass') }}">
    @component('content.render.blocks', [
        'renderData' => $renderData,
        'parentSettings' => $settings
    ])@endcomponent
</div>
