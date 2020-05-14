@php
    $column = $renderData->block;
    $content = $column->content;
    $settings = $column->settings;
@endphp

@includeIf('content.blocks.column.css')

<div class="column-block column-{{ $column->unique_id }} {{ $settings->get('customClass') }}">
    @component('content.render.blocks', [
        'renderData' => $renderData,
        'parentSettings' => $settings
    ])@endcomponent
</div>
