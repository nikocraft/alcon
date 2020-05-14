@push('content-block-custom-css')
    {{ $settings->get('css') }}
@endpush

@push('content-block-css')
.headline-block-{{ $block->unique_id }} {
    @cssproperty([ 'property' => 'font-family', 'value' => $settings->get('fontFamily') ]) @endcssproperty
    @cssproperty([ 'property' => 'font-size', 'value' => $settings->get('fontSize') ]) @endcssproperty
    @cssproperty([ 'property' => 'color', 'value' => $settings->get('textColor') ]) @endcssproperty
    @cssproperty([ 'property' => 'font-weight', 'value' => $settings->get('fontWeight') ]) @endcssproperty
    @cssproperty([ 'property' => 'margin', 'value' => $settings->get('margin') ]) @endcssproperty
    @cssproperty([ 'property' => 'padding', 'value' => $settings->get('padding') ]) @endcssproperty
    @cssproperty([ 'property' => 'background-color', 'value' => $settings->get('backgroundColor') ]) @endcssproperty
    @cssproperty([ 'property' => 'line-height', 'value' => $settings->get('fontLineHeight') ]) @endcssproperty
}
@endpush

@push('content-block-css-large')
.headline-block-{{ $block->unique_id }} {
    @cssproperty([ 'render' => $settings->get('fontSizeResponsive'), 'property' => 'font-size', 'value' => $settings->get('fontSizeLarge') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('fontLineHeightResponsive'), 'property' => 'line-height', 'value' => $settings->get('fontLineHeightLarge') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('paddingResponsive'), 'property' => 'padding', 'value' => $settings->get('paddingLarge') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('marginResponsive'), 'property' => 'margin', 'value' => $settings->get('marginLarge') ]) @endcssproperty
}
@endpush

@push('content-block-css-medium')
.headline-block-{{ $block->unique_id }} {
    @cssproperty([ 'render' => $settings->get('fontSizeResponsive'), 'property' => 'font-size', 'value' => $settings->get('fontSizeMedium') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('fontLineHeightResponsive'), 'property' => 'line-height', 'value' => $settings->get('fontLineHeightMedium') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('paddingResponsive'), 'property' => 'padding', 'value' => $settings->get('paddingMedium') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('marginResponsive'), 'property' => 'margin', 'value' => $settings->get('marginMedium') ]) @endcssproperty
}
@endpush

@push('content-block-css-small')
.headline-block-{{ $block->unique_id }} {
    @cssproperty([ 'render' => $settings->get('fontSizeResponsive'), 'property' => 'font-size', 'value' => $settings->get('fontSizeSmall') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('fontLineHeightResponsive'), 'property' => 'line-height', 'value' => $settings->get('fontLineHeightSmall') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('paddingResponsive'), 'property' => 'padding', 'value' => $settings->get('paddingSmall') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('marginResponsive'), 'property' => 'margin', 'value' => $settings->get('marginSmall') ]) @endcssproperty
}
@endpush

@push('content-block-css-extra-small')
.headline-block-{{ $block->unique_id }} {
    @cssproperty([ 'render' => $settings->get('fontSizeResponsive'), 'property' => 'font-size', 'value' => $settings->get('fontSizeExtraSmall') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('fontLineHeightResponsive'), 'property' => 'line-height', 'value' => $settings->get('fontLineHeightExtraSmall') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('paddingResponsive'), 'property' => 'padding', 'value' => $settings->get('paddingExtraSmall') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('marginResponsive'), 'property' => 'margin', 'value' => $settings->get('marginExtraSmall') ]) @endcssproperty
}
@endpush
