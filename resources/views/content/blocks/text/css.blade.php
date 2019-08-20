@push('content-block-custom-css')
    @if($settings->css){{ $settings->css }}@endif
@endpush

@push('content-block-css')
.text-{{ $renderData->block->unique_id }} {
    @if($settings->onClick == 'open-link')
    cursor: pointer;
    @endif
    font-size: {{ $settings->fontSize }};
    @if($settings->textColor)color: {{ $settings->textColor }};@endif
    @if($settings->textShadow != '0px 0px 0px transparent')text-shadow: {{ $settings->textShadow }};@endif
    @if($settings->margin)margin: {{ $settings->margin }};@endif
    @if($settings->padding)padding: {{ $settings->padding }};@endif
    @if($settings->backgroundColor)background-color: {{ $settings->backgroundColor }};@endif
    @if($settings->fontLineHeight)line-height: {{ $settings->fontLineHeight }};@endif
    @if(isset($parentSettings) && $parentSettings->display == 'flex')
        flex: {{ $settings->flex }};
        width: {{ $settings->width }};
        align-self: {{ $settings->alignSelf }};
    @else
        width: 100%;
    @endif
}

.text-{{ $renderData->block->unique_id }}:hover {
    @if($settings->textColorAdvanced && $settings->textColorHover)color: {{ $settings->textColorHover }};@endif
    @if($settings->backgroundColorAdvanced && $settings->backgroundColorHover)background-color: {{ $settings->backgroundColorHover }};@endif
}

.text-{{ $renderData->block->unique_id }}:active {
    @if($settings->textColorAdvanced && $settings->textColorActive)color: {{ $settings->textColorActive }};@endif
    @if($settings->backgroundColorAdvanced && $settings->backgroundColorActive)background-color: {{ $settings->backgroundColorActive }};@endif
}
@endpush

@push('content-block-css-large')
.text-{{ $renderData->block->unique_id }} {
    @if($settings->fontSizeResponsive)font-size: {{ $settings->fontSizeLarge }};@endif
    @if($settings->fontLineHeightResponsive)line-height: {{ $settings->fontLineHeightLarge }};@endif
    @if($settings->paddingResponsive)padding: {{ $settings->paddingLarge }};@endif
    @if($settings->marginResponsive)margin: {{ $settings->marginLarge }};@endif
    @if(isset($parentSettings) && $parentSettings->displayLarge == 'flex')
        flex: {{ $settings->flexLarge }};
        width: {{ $settings->widthLarge }};
        align-self: {{ $settings->alignSelfLarge }};
    @endif
}
@endpush

@push('content-block-css-medium')
.text-{{ $renderData->block->unique_id }} {
    @if($settings->fontSizeResponsive)font-size: {{ $settings->fontSizeMedium }};@endif
    @if($settings->fontLineHeightResponsive)line-height: {{ $settings->fontLineHeightMedium }};@endif
    @if($settings->paddingResponsive)padding: {{ $settings->paddingMedium }};@endif
    @if($settings->marginResponsive)margin: {{ $settings->marginMedium }};@endif
    @if(isset($parentSettings) && $parentSettings->displayMedium == 'flex')
        flex: {{ $settings->flexMedium }};
        width: {{ $settings->widthMedium }};
        align-self: {{ $settings->alignSelfMedium }};
    @endif
}
@endpush

@push('content-block-css-small')
.text-{{ $renderData->block->unique_id }} {
    @if($settings->fontSizeResponsive)font-size: {{ $settings->fontSizeSmall }};@endif
    @if($settings->fontLineHeightResponsive)line-height: {{ $settings->fontLineHeightSmall }};@endif
    @if($settings->paddingResponsive)padding: {{ $settings->paddingSmall }};@endif
    @if($settings->marginResponsive)margin: {{ $settings->marginSmall }};@endif
    @if(isset($parentSettings) && $parentSettings->displaySmall == 'flex')
        flex: {{ $settings->flexSmall }};
        width: {{ $settings->widthSmall }};
        align-self: {{ $settings->alignSelfSmall }};
    @endif
}
@endpush

@push('content-block-css-extra-small')
.text-{{ $renderData->block->unique_id }} {
    @if($settings->fontSizeResponsive)font-size: {{ $settings->fontSizeExtraSmall }};@endif
    @if($settings->fontLineHeightResponsive)line-height: {{ $settings->fontLineHeightExtraSmall }};@endif
    @if($settings->paddingResponsive)padding: {{ $settings->paddingExtraSmall }};@endif
    @if($settings->marginResponsive)margin: {{ $settings->marginExtraSmall }};@endif
    @if(isset($parentSettings) && $parentSettings->displayExtraSmall == 'flex')
        flex: {{ $settings->flexExtraSmall }};
        width: {{ $settings->widthExtraSmall }};
        align-self: {{ $settings->alignSelfExtraSmall }};
    @endif
}
@endpush
