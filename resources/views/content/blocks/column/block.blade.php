@php
    $column = $renderData->block;
    $content = $column->content;
    $settings = $column->settings;
@endphp

@includeIf('content.blocks.column.css')

<div class="column-block column-{{ $column->unique_id }} {{ $settings->get('customClass') }}" @if($settings->get('onClick') == 'open-link')onclick="window.open('{{ $settings->get('link') }}', '{{ $settings->get('target') }}');"@endif>
    @component('content.render.blocks', [
        'renderData' => $renderData,
        'parentSettings' => $settings
    ])@endcomponent
</div>
