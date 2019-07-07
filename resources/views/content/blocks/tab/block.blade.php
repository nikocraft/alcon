@php
    $column = $renderData->block;
    $settings = $column->getSettings();
    $activeClass = ($renderData->key==0) ? 'active-tab' : '';
@endphp

@includeIf('content.blocks.tab.css')

<div id="{{ $settings->blockRef }}" class="tab-block tab-{{ $column->unique_id }} {{ $activeClass }} {{ $settings->customClass }}">
    @component('content.render.blocks', [
        'renderData' => $renderData,
    ])@endcomponent
</div>
