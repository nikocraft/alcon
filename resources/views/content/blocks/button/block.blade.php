@php
    $block = $renderData->block;
    $settings = $block->settings;
@endphp

@includeIf('content.blocks.button.css')

<div class="btn button-block button-{{$block->unique_id}} {{$settings->get('textTag')}} {{ $settings->get('customClass') }}">
    <a href="{{ $settings->get('link') }}" target="{{ $settings->get('target') }}">
    {!! $block->content !!}
    </a>
</div>
