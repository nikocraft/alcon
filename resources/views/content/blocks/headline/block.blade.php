@php
    $settings = $renderData->block->getSettings();
@endphp

@includeIf('content.blocks.headline.css')

<{{$settings->headlineTag}} class="headline-block {{$settings->customClass}} headline-{{$renderData->block->unique_id}}" @if($settings->onClick == 'open-link')onclick="window.open('{{ $settings->link }}', '{{ $settings->target }}');"@endif>{!!$renderData->block->content!!}</{{$settings->headlineTag}}>
