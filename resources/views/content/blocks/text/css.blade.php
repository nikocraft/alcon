@push('content-block-custom-css')
    {{ $settings->get('css') }}
@endpush

@push('content-block-css')
.text-{{ $block->unique_id }} {
    @if($settings->get('onClick') == 'open-link')
    cursor: pointer;
    @endif
    font-size: {{ $settings->get('fontSize') }};
    @if($settings->get('textColor'))color: {{ $settings->get('textColor') }};@endif
    @if($settings->get('textShadow') != '0px 0px 0px transparent')text-shadow: {{ $settings->get('textShadow') }};@endif
    @if($settings->get('margin'))margin: {{ $settings->get('margin') }};@endif
    @if($settings->get('padding'))padding: {{ $settings->get('padding') }};@endif
    @if($settings->get('backgroundColor'))background-color: {{ $settings->get('backgroundColor') }};@endif
    @if($settings->get('fontLineHeight'))line-height: {{ $settings->get('fontLineHeight') }};@endif
    @if(isset($parentSettings) && $parentSettings->get('display') == 'flex')
        flex: {{ $settings->get('flex') }};
        width: {{ $settings->get('width') }};
        align-self: {{ $settings->get('alignSelf') }};
    @else
        width: 100%;
    @endif
}

.text-{{ $block->unique_id }}:hover {
    @if($settings->get('textColorAdvanced') && $settings->get('textColorHover'))color: {{ $settings->get('textColorHover') }};@endif
    @if($settings->get('backgroundColorAdvanced') && $settings->backgroundColorHover)background-color: {{ $settings->get('backgroundColorHover') }};@endif
}

.text-{{ $block->unique_id }}:active {
    @if($settings->get('textColorAdvanced') && $settings->get('textColorActive'))color: {{ $settings->get('textColorActive') }};@endif
    @if($settings->get('backgroundColorAdvanced') && $settings->get('backgroundColorActive'))background-color: {{ $settings->get('backgroundColorActive') }};@endif
}
@endpush

@push('content-block-css-large')
.text-{{ $block->unique_id }} {
    @if($settings->get('fontSizeResponsive'))font-size: {{ $settings->get('fontSizeLarge') }};@endif
    @if($settings->get('fontLineHeightResponsive'))line-height: {{ $settings->get('fontLineHeightLarge') }};@endif
    @if($settings->get('paddingResponsive'))padding: {{ $settings->get('paddingLarge') }};@endif
    @if($settings->get('marginResponsive'))margin: {{ $settings->get('marginLarge') }};@endif
    @if(isset($parentSettings) && $parentSettings->get('displayLarge') == 'flex')
        flex: {{ $settings->get('flexLarge') }};
        width: {{ $settings->get('widthLarge') }};
        align-self: {{ $settings->get('alignSelfLarge') }};
    @endif
}
@endpush

@push('content-block-css-medium')
.text-{{ $block->unique_id }} {
    @if($settings->get('fontSizeResponsive'))font-size: {{ $settings->get('fontSizeMedium') }};@endif
    @if($settings->get('fontLineHeightResponsive'))line-height: {{ $settings->get('fontLineHeightMedium') }};@endif
    @if($settings->get('paddingResponsive'))padding: {{ $settings->get('paddingMedium') }};@endif
    @if($settings->get('marginResponsive'))margin: {{ $settings->get('marginMedium') }};@endif
    @if(isset($parentSettings) && $parentSettings->get('displayMedium') == 'flex')
        flex: {{ $settings->get('flexMedium') }};
        width: {{ $settings->get('widthMedium') }};
        align-self: {{ $settings->get('alignSelfMedium') }};
    @endif
}
@endpush

@push('content-block-css-small')
.text-{{ $block->unique_id }} {
    @if($settings->get('fontSizeResponsive'))font-size: {{ $settings->get('fontSizeSmall') }};@endif
    @if($settings->get('fontLineHeightResponsive'))line-height: {{ $settings->get('fontLineHeightSmall') }};@endif
    @if($settings->get('paddingResponsive'))padding: {{ $settings->get('paddingSmall') }};@endif
    @if($settings->get('marginResponsive'))margin: {{ $settings->get('marginSmall') }};@endif
    @if(isset($parentSettings) && $parentSettings->get('displaySmall') == 'flex')
        flex: {{ $settings->get('flexSmall') }};
        width: {{ $settings->get('widthSmall') }};
        align-self: {{ $settings->get('alignSelfSmall') }};
    @endif
}
@endpush

@push('content-block-css-extra-small')
.text-{{ $block->unique_id }} {
    @if($settings->get('fontSizeResponsive'))font-size: {{ $settings->get('fontSizeExtraSmall') }};@endif
    @if($settings->get('fontLineHeightResponsive'))line-height: {{ $settings->get('fontLineHeightExtraSmall') }};@endif
    @if($settings->get('paddingResponsive'))padding: {{ $settings->get('paddingExtraSmall') }};@endif
    @if($settings->get('marginResponsive'))margin: {{ $settings->get('marginExtraSmall') }};@endif
    @if(isset($parentSettings) && $parentSettings->get('displayExtraSmall') == 'flex')
        flex: {{ $settings->get('flexExtraSmall') }};
        width: {{ $settings->get('widthExtraSmall') }};
        align-self: {{ $settings->get('alignSelfExtraSmall') }};
    @endif
}
@endpush
