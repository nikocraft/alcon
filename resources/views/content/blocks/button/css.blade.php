@push('content-block-custom-css')
    @if($settings->get('css')){{ $settings->get('css') }}@endif
@endpush

@push('content-block-css')
.button-{{ $block->unique_id }} {
    line-height: {{ $settings->get('fontLineHeight') }};
    font-size: {{ $settings->get('fontSize') }};
    font-weight: {{ $settings->get('fontWeight') }};
    color: {{ $settings->get('textColor') }};
    background-color: {{ $settings->get('backgroundColor') }};
    text-shadow: {{ $settings->get('textShadow') }};
    box-shadow: {{ $settings->get('boxShadow') }};
    border: {{ $settings->get('border') }};
    border-radius: {{ $settings->get('borderRadius') }};
    margin: {{ $settings->get('margin') }};
    padding: {{ $settings->get('padding') }};
    @if(isset($parentSettings) && $parentSettings->display == 'flex')
        flex: {{ $settings->get('flex') }};
        width: {{ $settings->get('width') }};
        align-self: {{ $settings->get('alignSelf') }};
    @else
        width: 100%;
    @endif
}

.button-{{ $block->unique_id }}:hover a {
    text-decoration: none;
}

.button-{{ $block->unique_id }} > a {
    display: block;
    color: unset;
}

.button-{{ $block->unique_id }}.btn:hover {
    @if($settings->get('textColorAdvanced'))color: {{ $settings->get('textColorHover') }};@endif
    @if($settings->get('backgroundColorAdvanced'))background-color: {{ $settings->get('backgroundColorHover') }};@endif
    @if($settings->get('textShadowAdvanced'))text-shadow: {{ $settings->get('textShadowHover') }};@endif
    @if($settings->get('boxShadowAdvanced'))box-shadow: {{ $settings->get('boxShadowHover') }};@endif
    @if($settings->get('borderAdvanced'))border: {{ $settings->get('borderHover') }};@endif
}

.button-{{ $block->unique_id }}.btn:active {
    @if($settings->get('textColorAdvanced'))color: {{ $settings->get('textColorActive') }};@endif
    @if($settings->get('backgroundColorAdvanced'))background-color: {{ $settings->get('backgroundColorActive') }};@endif
    @if($settings->get('textShadowAdvanced'))text-shadow: {{ $settings->get('textShadowActive') }};@endif
    @if($settings->get('boxShadowAdvanced'))box-shadow: {{ $settings->get('boxShadowActive') }};@endif
    @if($settings->get('borderAdvanced'))border: {{ $settings->get('borderActive') }};@endif
}

.button-{{ $block->unique_id }}.btn:focus {
    @if($settings->textColorAdvanced)color: {{ $settings->get('textColorFocus') }};@endif
    @if($settings->get('backgroundColorAdvanced'))background-color: {{ $settings->get('backgroundColorFocus') }};@endif
    @if($settings->get('textShadowAdvanced'))text-shadow: {{ $settings->get('textShadowFocus') }};@endif
    @if($settings->get('boxShadowAdvanced'))box-shadow: {{ $settings->get('boxShadowFocus') }};@endif
    @if($settings->get('borderAdvanced'))border: {{ $settings->get('borderFocus') }};@endif
}
@endpush

@push('content-block-css-large')
.button-{{ $block->unique_id }} .btn {
    font-size: {{ $settings->get('fontSizeLarge') }};
    line-height: {{ $settings->get('fontLineHeightLarge') }};
    padding: {{ $settings->get('paddingLarge') }};
    margin: {{ $settings->get('marginLarge') }};

    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayLarge == 'flex'))
        flex: {{ $settings->get('flexLarge') }};
        align-self: {{ $settings->get('alignSelfLarge') }};
    @endif
    width: {{ $settings->get('widthLarge') }};
}
@endpush

@push('content-block-css-medium')
.button-{{ $block->unique_id }} .btn {
    font-size: {{ $settings->get('fontSizeMedium') }};
    line-height: {{ $settings->get('fontLineHeightMedium') }};
    padding: {{ $settings->get('paddingMedium') }};
    margin: {{ $settings->get('marginMedium') }};

    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayMedium == 'flex'))
        flex: {{ $settings->get('flexMedium') }};
        align-self: {{ $settings->get('alignSelfMedium') }};
    @endif
    width: {{ $settings->get('widthMedium') }};
}
@endpush

@push('content-block-css-small')
.button-{{ $block->unique_id }} .btn {
    font-size: {{ $settings->get('fontSizeSmall') }};
    line-height: {{ $settings->get('fontLineHeightSmall') }};
    padding: {{ $settings->get('paddingSmall') }};
    margin: {{ $settings->get('marginSmall') }};
    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displaySmall == 'flex'))
        flex: {{ $settings->get('flexSmall') }};
        align-self: {{ $settings->get('alignSelfSmall') }};
    @endif
    width: {{ $settings->get('widthSmall') }};
}
@endpush

@push('content-block-css-extra-small')
.button-{{ $block->unique_id }} .btn {
    font-size: {{ $settings->get('fontSizeExtraSmall') }};
    line-height: {{ $settings->get('fontLineHeightExtraSmall') }};
    padding: {{ $settings->get('paddingExtraSmall') }};
    margin: {{ $settings->get('marginExtraSmall') }};
    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayExtraSmall == 'flex'))
        flex: {{ $settings->get('flexExtraSmall') }};
        align-self: {{ $settings->get('alignSelfExtraSmall') }};
    @endif
    width: {{ $settings->get('widthExtraSmall') }};
}
@endpush
