@php
    $settings = $renderData->block->getSettings();
    $renderData->block->content = nl2br($renderData->block->content);
    $parentDisplay = isset($renderData->display) ? $renderData->display : 'block';
@endphp

@includeIf('content.blocks.text.css')


<div class="text-block text-{{ $renderData->block->unique_id }} {{ $settings->customClass }}" style="text-align: {{ $settings->textAlign }}" @if($settings->onClick == 'open-link')onclick="window.open('{{ $settings->link }}', '{{ $settings->target }}');"@endif>
    {{-- <h3>{{ $settings->blockTitle }}</h3> --}}
    {!! $renderData->block->content !!}
</div>
