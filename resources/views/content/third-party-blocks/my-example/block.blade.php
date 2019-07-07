@php
    $settings = $renderData->block->getSettings();
@endphp

@includeIf('content.third-party-blocks.my-example.css')

<div>
	Width: {{ $settings->width }}<br>
    <div>{!! $renderData->block->content !!}</div>
</div>
