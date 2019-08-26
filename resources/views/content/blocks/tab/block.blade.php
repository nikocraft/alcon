@php
    $column = $renderData->block;
    $settings = $column->settings;
    $activeClass = ($renderData->key==0) ? 'active-tab' : '';
@endphp

@includeIf('content.blocks.tab.css')

<div id="{{ $settings->get('blockRef') }}" class="tab-block tab-{{ $column->unique_id }} {{ $activeClass }} {{ $settings->get('customClass') }}">
    @component('content.render.blocks', [
        'renderData' => $renderData,
    ])@endcomponent
</div>
