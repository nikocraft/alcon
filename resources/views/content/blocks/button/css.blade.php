@push('content-block-custom-css')
    @if($settings->css){{ $settings->css }}@endif
@endpush

@push('content-block-css')
.button-{{ $renderData->block->unique_id }} {
    line-height: {{ $settings->fontLineHeight }};
    font-size: {{ $settings->fontSize }};
    font-weight: {{ $settings->fontWeight }};
    color: {{ $settings->textColor }};
    background-color: {{ $settings->backgroundColor }};
    text-shadow: {{ $settings->textShadow }};
    box-shadow: {{ $settings->boxShadow }};
    border: {{ $settings->border }};
    border-radius: {{ $settings->borderRadius }};
    margin: {{ $settings->margin }};
    padding: {{ $settings->padding }};
    @if(isset($parentSettings) && $parentSettings->display == 'flex')
        flex: {{ $settings->flex }};
        width: {{ $settings->width }};
        align-self: {{ $settings->alignSelf }};
    @else
        width: 100%;
    @endif
}

.button-{{ $renderData->block->unique_id }}:hover a {
    text-decoration: none;
}

.button-{{ $renderData->block->unique_id }} > a {
    display: block;
    color: unset;
}

.button-{{ $renderData->block->unique_id }}.btn:hover {
    @if($settings->textColorAdvanced)color: {{ $settings->textColorHover }};@endif
    @if($settings->backgroundColorAdvanced)background-color: {{ $settings->backgroundColorHover }};@endif
    @if($settings->textShadowAdvanced)text-shadow: {{ $settings->textShadowHover }};@endif
    @if($settings->boxShadowAdvanced)box-shadow: {{ $settings->boxShadowHover }};@endif
    @if($settings->borderAdvanced)border: {{ $settings->borderHover }};@endif
}

.button-{{ $renderData->block->unique_id }}.btn:active {
    @if($settings->textColorAdvanced)color: {{ $settings->textColorActive }};@endif
    @if($settings->backgroundColorAdvanced)background-color: {{ $settings->backgroundColorActive }};@endif
    @if($settings->textShadowAdvanced)text-shadow: {{ $settings->textShadowActive }};@endif
    @if($settings->boxShadowAdvanced)box-shadow: {{ $settings->boxShadowActive }};@endif
    @if($settings->borderAdvanced)border: {{ $settings->borderActive }};@endif
}

.button-{{ $renderData->block->unique_id }}.btn:focus {
    @if($settings->textColorAdvanced)color: {{ $settings->textColorFocus }};@endif
    @if($settings->backgroundColorAdvanced)background-color: {{ $settings->backgroundColorFocus }};@endif
    @if($settings->textShadowAdvanced)text-shadow: {{ $settings->textShadowFocus }};@endif
    @if($settings->boxShadowAdvanced)box-shadow: {{ $settings->boxShadowFocus }};@endif
    @if($settings->borderAdvanced)border: {{ $settings->borderFocus }};@endif
}
@endpush

@push('content-block-css-large')
.button-{{ $renderData->block->unique_id }} .btn {
    font-size: {{ $settings->fontSizeLarge }};
    line-height: {{ $settings->fontLineHeightLarge }};
    padding: {{ $settings->paddingLarge }};
    margin: {{ $settings->marginLarge }};
    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayLarge == 'flex'))
        flex: {{ $settings->flexLarge }};
        align-self: {{ $settings->alignSelfLarge }};
    @endif
    width: {{ $settings->widthLarge }};
}
@endpush

@push('content-block-css-medium')
.button-{{ $renderData->block->unique_id }} .btn {
    font-size: {{ $settings->fontSizeMedium }};
    line-height: {{ $settings->fontLineHeightMedium }};
    padding: {{ $settings->paddingMedium }};
    margin: {{ $settings->marginMedium }};
    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayMedium == 'flex'))
        flex: {{ $settings->flexMedium }};
        align-self: {{ $settings->alignSelfMedium }};
    @endif
    width: {{ $settings->widthMedium }};
}
@endpush

@push('content-block-css-small')
.button-{{ $renderData->block->unique_id }} .btn {
    font-size: {{ $settings->fontSizeSmall }};
    line-height: {{ $settings->fontLineHeightSmall }};
    padding: {{ $settings->paddingSmall }};
    margin: {{ $settings->marginSmall }};
    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displaySmall == 'flex'))
        flex: {{ $settings->flexSmall }};
        align-self: {{ $settings->alignSelfSmall }};
    @endif
    width: {{ $settings->widthSmall }};
}
@endpush

@push('content-block-css-extra-small')
.button-{{ $renderData->block->unique_id }} .btn {
    font-size: {{ $settings->fontSizeExtraSmall }};
    line-height: {{ $settings->fontLineHeightExtraSmall }};
    padding: {{ $settings->paddingExtraSmall }};
    margin: {{ $settings->marginExtraSmall }};
    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayExtraSmall == 'flex'))
        flex: {{ $settings->flexExtraSmall }};
        align-self: {{ $settings->alignSelfExtraSmall }};
    @endif
    width: {{ $settings->widthExtraSmall }};
}
@endpush
