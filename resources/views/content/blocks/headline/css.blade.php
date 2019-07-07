@push('content-block-custom-css')
    @if($settings->css){{ $settings->css }}@endif
@endpush

@push('content-block-css')
.headline-{{ $renderData->block->unique_id }} {
    @if($settings->onClick == 'open-link')
    cursor: pointer;
    @endif
    font-size: {{ $settings->fontSize }};
    font-weight: {{ $settings->fontWeight }};
    color: {{ $settings->textColor }};
    text-shadow: {{ $settings->textShadow }};
    margin: {{ $settings->margin }};
    padding: {{ $settings->padding }};
    background-color: {{ $settings->backgroundColor }};
    line-height: {{ $settings->fontLineHeight }};
    {{-- display: {{ $settings->display }}; --}}
    font-family: "{{ $settings->fontFamily }}";

    @if(isset($parentSettings) && $parentSettings->display == 'flex')
        flex: {{ $settings->flex }};
        width: {{ $settings->width }};
        align-self: {{ $settings->alignSelf }};
    @else
        width: 100%;
    @endif
}
.headline-{{ $renderData->block->unique_id }}:hover {
    @if($settings->textColorAdvanced)color: {{ $settings->textColorHover }};@endif
    @if($settings->backgroundColorAdvanced)background-color: {{ $settings->backgroundColorHover }};@endif
    @if($settings->textShadowAdvanced)text-shadow: {{ $settings->textShadowHover }};@endif
}

.headline-{{ $renderData->block->unique_id }}:active {
    @if($settings->textColorAdvanced)color: {{ $settings->textColorActive }};@endif
    @if($settings->backgroundColorAdvanced)background-color: {{ $settings->backgroundColorActive }};@endif
    @if($settings->textShadowAdvanced)text-shadow: {{ $settings->textShadowActive }};@endif
}
@endpush

@push('content-block-css-large')
.headline-{{ $renderData->block->unique_id }} {
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
.headline-{{ $renderData->block->unique_id }} {
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
.headline-{{ $renderData->block->unique_id }} {
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
.headline-{{ $renderData->block->unique_id }} {
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
