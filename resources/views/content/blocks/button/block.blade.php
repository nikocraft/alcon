@php
    $block = $renderData->block;
    $settings = $block->settings;
    $parentDisplay = isset($renderData->display) ? $renderData->display : 'block';
    // $parentFlexDirection = $renderData->flexDirection;
@endphp

@includeIf('content.blocks.button.css')

<div class="btn button-block button-{{$block->unique_id}} {{$settings->get('textTag')}} {{ $settings->get('customClass') }}">
    <a href="{{ $settings->get('link') }}" target="{{ $settings->get('target') }}">
    {!! $block->content !!}
    </a>
</div>
