@php
    $block = $renderData->block;
    $settings = $block->settings;
@endphp

@includeIf('content.blocks.headline.css')

<{{ $settings->get('headlineTag', 'h2') }} class="headline-block {{$settings->get('customClass')}} headline-block-{{$block->unique_id}}">
    {!!$block->content!!}
</{{ $settings->get('headlineTag', 'h2') }}>
