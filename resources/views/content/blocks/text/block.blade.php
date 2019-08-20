@php
    $settings = $renderData->block->settings;
    $renderData->block->content = nl2br($renderData->block->content);
    $parentDisplay = isset($renderData->display) ? $renderData->display : 'block';
@endphp

@includeIf('content.blocks.text.css')


<div class="text-block text-{{ $renderData->block->unique_id }} {{ $settings->get('customClass') }}" @if($settings->onClick == 'open-link')onclick="window.open('{{ $settings->get('link') }}', '{{ $settings->get('target') }}');"@endif>
    {{-- <h3>{{ $settings->blockTitle }}</h3> --}}
    {!! $renderData->block->content !!}
</div>
