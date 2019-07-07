@php
    $settings = $renderData->block->getSettings();
    $parentDisplay = isset($renderData->display) ? $renderData->display : 'block';
    // $parentFlexDirection = $renderData->flexDirection;
@endphp

@includeIf('content.blocks.button.css')

<div class="btn button-block button-{{$renderData->block->unique_id}} {{$settings->textTag}} {{ $settings->customClass }}">
    <a href="{{ $settings->link }}" target="{{ $settings->target }}">
    {!!$renderData->block->content!!}
    </a>
</div>
