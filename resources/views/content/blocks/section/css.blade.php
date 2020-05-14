@push('content-block-custom-css')
    {{ $settings->get('css') }}
@endpush

@push('content-block-css')
.section-{{ $block->unique_id }} {
    @cssproperty([ 'property' => 'height', 'value' => $settings->get('height') ]) @endcssproperty
    @cssproperty([ 'property' => 'padding', 'value' => $settings->get('padding') ]) @endcssproperty
    @cssproperty([ 'property' => 'margin', 'value' => $settings->get('margin') ]) @endcssproperty
    @cssproperty([ 'property' => 'width', 'value' => $settings->get('width') ]) @endcssproperty

    @if($settings->backgroundImage)
        background-image: url('{{ $settings->get('backgroundImage') }}');
        @cssproperty([ 'property' => 'background-attachment', 'value' => $settings->get('backgroundStyle') ]) @endcssproperty
        @cssproperty([ 'property' => 'background-position', 'value' => $settings->get('backgroundPosition') ]) @endcssproperty
        @cssproperty([ 'property' => 'background-size', 'value' => $settings->get('backgroundSize') ]) @endcssproperty
        @cssproperty([ 'property' => 'background-repeat', 'value' => $settings->get('backgroundRepeat') ]) @endcssproperty
        {{-- box-shadow: inset 0 0 0 2000px {{$settings->backgroundColor}}; --}}
    @else
    @cssproperty([ 'property' => 'background-color', 'value' => $settings->get('backgroundColor') ]) @endcssproperty
    @endif
}
@endpush

@push('content-block-css-large')
.section-{{ $block->unique_id }} {
    @cssproperty([ 'property' => 'height', 'value' => $settings->get('heightLarge') ]) @endcssproperty
    @cssproperty([ 'property' => 'padding', 'value' => $settings->get('paddingLarge')]) @endcssproperty
    @cssproperty([ 'property' => 'margin', 'value' => $settings->get('marginLarge') ]) @endcssproperty
}
@endpush

@push('content-block-css-medium')
.section-{{ $block->unique_id }} {
    @cssproperty([ 'property' => 'height', 'value' => $settings->get('heightMedium') ]) @endcssproperty
    @cssproperty([ 'property' => 'padding', 'value' => $settings->get('paddingMedium')]) @endcssproperty
    @cssproperty([ 'property' => 'margin', 'value' => $settings->get('marginMedium') ]) @endcssproperty
}
@endpush

@push('content-block-css-small')
.section-{{ $block->unique_id }} {
    @cssproperty([ 'property' => 'height', 'value' => $settings->get('heightSmall') ]) @endcssproperty
    @cssproperty([ 'property' => 'padding', 'value' => $settings->get('paddingSmall')]) @endcssproperty
    @cssproperty([ 'property' => 'margin', 'value' => $settings->get('marginSmall') ]) @endcssproperty
}
@endpush

@push('content-block-css-extra-small')
.section-{{ $block->unique_id }} {
    @cssproperty([ 'property' => 'height', 'value' => $settings->get('heightExtraSmall') ]) @endcssproperty
    @cssproperty([ 'property' => 'padding', 'value' => $settings->get('paddingExtraSmall')]) @endcssproperty
    @cssproperty([ 'property' => 'margin', 'value' => $settings->get('marginExtraSmall') ]) @endcssproperty
}
@endpush
