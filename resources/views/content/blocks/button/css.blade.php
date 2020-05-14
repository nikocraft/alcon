@push('content-block-custom-css')
    @if($settings->get('css')){{ $settings->get('css') }}@endif
@endpush

@push('content-block-css')
.button-{{ $block->unique_id }} {
    @cssproperty([ 'property' => 'line-height', 'value' => $settings->get('fontLineHeight') ]) @endcssproperty
    @cssproperty([ 'property' => 'font-size', 'value' => $settings->get('fontSize') ]) @endcssproperty
    @cssproperty([ 'property' => 'font-weight', 'value' => $settings->get('fontWeight') ]) @endcssproperty
    @cssproperty([ 'property' => 'color', 'value' => $settings->get('textColor') ]) @endcssproperty
    @cssproperty([ 'property' => 'background-color', 'value' => $settings->get('backgroundColor') ]) @endcssproperty
    @cssproperty([ 'property' => 'text-shadow', 'value' => $settings->get('textShadow') ]) @endcssproperty
    @cssproperty([ 'property' => 'box-shadow', 'value' => $settings->get('boxShadow') ]) @endcssproperty
    @cssproperty([ 'property' => 'border', 'value' => $settings->get('border') ]) @endcssproperty
    @cssproperty([ 'property' => 'border-radius', 'value' => $settings->get('borderRadius') ]) @endcssproperty
    @cssproperty([ 'property' => 'margin', 'value' => $settings->get('margin') ]) @endcssproperty
    @cssproperty([ 'property' => 'padding', 'value' => $settings->get('padding') ]) @endcssproperty
}

.button-{{ $block->unique_id }}:hover a {
    text-decoration: none;
}

.button-{{ $block->unique_id }} > a {
    display: block;
    color: unset;
}

.button-{{ $block->unique_id }}.btn:hover {
    @cssproperty([ 'render' => $settings->get('textColorAdvanced'), 'property' => 'color', 'value' => $settings->get('textColorHover') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('backgroundColorAdvanced'), 'property' => 'background-color', 'value' => $settings->get('backgroundColorHover') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('textShadowAdvanced'), 'property' => 'text-shadow', 'value' => $settings->get('textShadowHover') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('boxShadowAdvanced'), 'property' => 'box-shadow', 'value' => $settings->get('boxShadowHover') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('borderAdvanced'), 'property' => 'border', 'value' => $settings->get('borderHover') ]) @endcssproperty
}

.button-{{ $block->unique_id }}.btn:active {
    @cssproperty([ 'render' => $settings->get('textColorAdvanced'), 'property' => 'color', 'value' => $settings->get('textColorActive') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('backgroundColorAdvanced'), 'property' => 'background-color', 'value' => $settings->get('backgroundColorActive') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('textShadowAdvanced'), 'property' => 'text-shadow', 'value' => $settings->get('textShadowActive') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('boxShadowAdvanced'), 'property' => 'box-shadow', 'value' => $settings->get('boxShadowActive') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('borderAdvanced'), 'property' => 'border', 'value' => $settings->get('borderActive') ]) @endcssproperty
}

.button-{{ $block->unique_id }}.btn:focus {
    @cssproperty([ 'render' => $settings->get('textColorAdvanced'), 'property' => 'color', 'value' => $settings->get('textColorFocus') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('backgroundColorAdvanced'), 'property' => 'background-color', 'value' => $settings->get('backgroundColorFocus') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('textShadowAdvanced'), 'property' => 'text-shadow', 'value' => $settings->get('textShadowFocus') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('boxShadowAdvanced'), 'property' => 'box-shadow', 'value' => $settings->get('boxShadowFocus') ]) @endcssproperty
    @cssproperty([ 'render' => $settings->get('borderAdvanced'), 'property' => 'border', 'value' => $settings->get('borderFocus') ]) @endcssproperty
}
@endpush

@push('content-block-css-large')
.button-{{ $block->unique_id }} .btn {
    @cssproperty([ 'property' => 'line-height', 'value' => $settings->get('fontLineHeightLarge') ]) @endcssproperty
    @cssproperty([ 'property' => 'font-size', 'value' => $settings->get('fontSizeLarge') ]) @endcssproperty
    @cssproperty([ 'property' => 'padding', 'value' => $settings->get('paddingLarge') ]) @endcssproperty
    @cssproperty([ 'property' => 'margin', 'value' => $settings->get('marginLarge') ]) @endcssproperty
}
@endpush

@push('content-block-css-medium')
.button-{{ $block->unique_id }} .btn {
    @cssproperty([ 'property' => 'line-height', 'value' => $settings->get('fontSizeMedium') ]) @endcssproperty
    @cssproperty([ 'property' => 'font-size', 'value' => $settings->get('fontLineHeightMedium') ]) @endcssproperty
    @cssproperty([ 'property' => 'padding', 'value' => $settings->get('paddingMedium') ]) @endcssproperty
    @cssproperty([ 'property' => 'margin', 'value' => $settings->get('marginMedium') ]) @endcssproperty
}
@endpush

@push('content-block-css-small')
.button-{{ $block->unique_id }} .btn {
    @cssproperty([ 'property' => 'line-height', 'value' => $settings->get('fontSizeSmall') ]) @endcssproperty
    @cssproperty([ 'property' => 'font-size', 'value' => $settings->get('fontLineHeightSmall') ]) @endcssproperty
    @cssproperty([ 'property' => 'padding', 'value' => $settings->get('paddingSmall') ]) @endcssproperty
    @cssproperty([ 'property' => 'margin', 'value' => $settings->get('marginSmall') ]) @endcssproperty
}
@endpush

@push('content-block-css-extra-small')
.button-{{ $block->unique_id }} .btn {
    @cssproperty([ 'property' => 'line-height', 'value' => $settings->get('fontSizeExtraSmall') ]) @endcssproperty
    @cssproperty([ 'property' => 'font-size', 'value' => $settings->get('fontLineHeightExtraSmall') ]) @endcssproperty
    @cssproperty([ 'property' => 'padding', 'value' => $settings->get('paddingExtraSmall') ]) @endcssproperty
    @cssproperty([ 'property' => 'margin', 'value' => $settings->get('marginExtraSmall') ]) @endcssproperty
}
@endpush
