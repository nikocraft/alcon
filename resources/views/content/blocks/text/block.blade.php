@php
    $block = $renderData->block;
    $settings = $block->settings;
    $block->content = nl2br($block->content);
    $parentDisplay = isset($renderData->display) ? $renderData->display : 'block';
@endphp

@includeIf('content.blocks.text.css')

<div class="text-block text-{{ $block->unique_id }} {{ $settings->get('customClass') }}">
    {!! $block->content !!}
</div>
