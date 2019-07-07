@push('content-block-custom-css')
    @if($settings->css){{ $settings->css }}@endif
@endpush

@push('content-block-css')
.slider-{{ $currentBlock->unique_id }} {
    height: {{ $settings->height }};
    margin: {{ $settings->margin }};
    {{-- padding: {{ $settings->padding }}; --}}
    @if(isset($parentSettings) && $parentSettings->display == 'flex')
        flex: {{ $settings->flex }};
        width: {{ $settings->width }};
        align-self: {{ $settings->alignSelf }};
    @else
        width: 100%;
    @endif
}
@endpush


@push('content-block-css-large')
.slider-{{ $currentBlock->unique_id }} {
    @if($settings->heightResponsive)height: {{$settings->heightLarge}};@endif
    {{-- @if($settings->paddingResponsive)padding: {{$settings->paddingLarge}};@endif --}}
    @if($settings->marginResponsive)margin: {{$settings->marginLarge}};@endif

    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayLarge == 'flex'))
        flex: {{ $settings->flexLarge }};
        align-self: {{ $settings->alignSelfLarge }};
    @endif
    width: {{ $settings->widthLarge }};
}
@endpush

@push('content-block-css-medium')
.slider-{{ $currentBlock->unique_id }} {
    @if($settings->heightResponsive)height: {{$settings->heightMedium}};@endif
    {{-- @if($settings->paddingResponsive)padding: {{$settings->paddingMedium}}; --}}
    @if($settings->marginResponsive)margin: {{$settings->marginMedium}};@endif

    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayMedium == 'flex'))
        flex: {{ $settings->flexMedium }};
        align-self: {{ $settings->alignSelfMedium }};
    @endif
    width: {{ $settings->widthMedium }};
}
@endpush

@push('content-block-css-small')
.slider-{{ $currentBlock->unique_id }} {
    @if($settings->heightResponsive)height: {{$settings->heightSmall}};@endif
    {{-- @if($settings->paddingResponsive)padding: {{$settings->paddingSmall}}; --}}
    @if($settings->marginResponsive)margin: {{$settings->marginSmall}};@endif

    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displaySmall == 'flex'))
        flex: {{ $settings->flexSmall }};
        align-self: {{ $settings->alignSelfSmall }};
    @endif
    width: {{ $settings->widthSmall }};
}
@endpush

@push('content-block-css-extra-small')
.slider-{{ $currentBlock->unique_id }} {
    @if($settings->heightResponsive)height: {{$settings->heightExtraSmall}};@endif
    {{-- @if($settings->paddingResponsive)padding: {{$settings->paddingExtraSmall}}; --}}
    @if($settings->marginResponsive)margin: {{$settings->marginExtraSmall}};@endif

    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayExtraSmall == 'flex'))
        flex: {{ $settings->flexExtraSmall }};
        align-self: {{ $settings->alignSelfExtraSmall }};
    @endif
    width: {{ $settings->widthExtraSmall }};
}
@endpush
