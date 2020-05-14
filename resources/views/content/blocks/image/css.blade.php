@push('content-block-custom-css')
    {{ $settings->get('css') }}
@endpush

@push('content-block-css')
.image-{{ $block->unique_id }} {
    display: flex;
    align-items: center;
    @cssproperty([ 'property' => 'justify-content', 'value' => $settings->get('imagePosition') ]) @endcssproperty
    @cssproperty([ 'property' => 'padding', 'value' => $settings->get('padding') ]) @endcssproperty
    @cssproperty([ 'property' => 'margin', 'value' => $settings->get('margin') ]) @endcssproperty
    @cssproperty([ 'property' => 'background-color', 'value' => $settings->get('backgroundColor') ]) @endcssproperty
}

.image-{{ $block->unique_id }} img {
    @cssproperty([ 'property' => 'border', 'value' => $settings->get('imageBorder') ]) @endcssproperty
    @cssproperty([ 'property' => 'border-radius', 'value' => $settings->get('imageBorderRadius') ]) @endcssproperty
    @if($settings->get('imageResponsive') == false)
        @cssproperty([ 'property' => 'width', 'value' => $settings->get('width') ]) @endcssproperty
    @endif
}
@endpush

@push('content-block-css-large')
.image-{{ $block->unique_id }} {
    @cssproperty([ 'property' => 'padding', 'value' => $settings->get('paddingLarge')]) @endcssproperty
    @cssproperty([ 'property' => 'margin', 'value' => $settings->get('marginLarge') ]) @endcssproperty
}
@endpush

@push('content-block-css-medium')
.image-{{ $block->unique_id }} {
    @cssproperty([ 'property' => 'padding', 'value' => $settings->get('paddingMedium')]) @endcssproperty
    @cssproperty([ 'property' => 'margin', 'value' => $settings->get('marginMedium') ]) @endcssproperty
}
@endpush

@push('content-block-css-small')
.image-{{ $block->unique_id }} {
    @cssproperty([ 'property' => 'padding', 'value' => $settings->get('paddingSmall')]) @endcssproperty
    @cssproperty([ 'property' => 'margin', 'value' => $settings->get('marginSmall') ]) @endcssproperty
}
@endpush

@push('content-block-css-extra-small')
.image-{{ $block->unique_id }} {
    @cssproperty([ 'property' => 'padding', 'value' => $settings->get('paddingExtraSmall')]) @endcssproperty
    @cssproperty([ 'property' => 'margin', 'value' => $settings->get('marginExtraSmall') ]) @endcssproperty
}
@endpush
