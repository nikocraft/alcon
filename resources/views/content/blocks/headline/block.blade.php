@php
    $block = $renderData->block;
    $settings = $block->settings;
@endphp

@includeIf('content.blocks.headline.css')

<{{$settings->get('headlineTag', 'h2')}} @if($settings->get('onClick') == 'open-link')onclick="window.open('{{ $settings->get('link') }}', '{{ $settings->get('target') }}');"@endif
    class="headline-block {{$settings->get('customClass')}} headline-{{$block->unique_id}}">
    {!!$block->content!!}
</{{$settings->get('headlineTag', 'h2')}}>
