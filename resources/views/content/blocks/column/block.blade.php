@php
    $column = $renderData->block;
    $content = $renderData->block->content;
    $settings = $column->getSettings();
@endphp

@includeIf('content.blocks.column.css')

<div class="column-block column-{{ $column->unique_id }} {{ $settings->customClass }}" @if($settings->onClick == 'open-link')onclick="window.open('{{ $settings->link }}', '{{ $settings->target }}');"@endif>
    @component('content.render.blocks', [
        'renderData' => $renderData,
        'parentSettings' => $settings
    ])@endcomponent
</div>
