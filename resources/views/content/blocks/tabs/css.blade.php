@push('content-block-custom-css')
    @if($settings->css){{ $settings->css }}@endif
@endpush

@push('content-block-css')
.tabs-{{ $renderData->block->unique_id }} {
    height: {{$settings->heightLarge}};
    padding: {{$settings->padding}};
    margin: {{$settings->margin}};
    {{-- padding: 1px; --}}

    @if($settings->tabNavPosition != 'top')
        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
    @endif

    @if(isset($parentSettings) && $parentSettings->display == 'flex')
        flex: {{ $settings->flex }};
        width: {{ $settings->width }};
        align-self: {{ $settings->alignSelf }};
    @else
        width: 100%;
    @endif
}

.tabs-{{ $renderData->block->unique_id }} .tabsbar {
    flex: 0.2;
    margin: {{$settings->tabNavMargin}};
    @if($settings->tabNavPosition == 'right')
    order: 2;
    @endif
}
@if($settings->tabNavPosition != 'top')
.tabs-{{ $renderData->block->unique_id }} .tabsbar .tabsbar-item {
    width: 100%;
    margin-bottom: 5px;
}
@endif

.tabs-{{ $renderData->block->unique_id }} .tab-block {
    flex: 0.8;
}
@endpush

@push('content-block-css-large')
.tabs-{{ $renderData->block->unique_id }} {
    @if($settings->heightResponsive)height: {{$settings->heightLarge}};@endif
    @if($settings->paddingResponsive)padding: {{$settings->paddingLarge}};@endif
    @if($settings->marginResponsive)margin: {{$settings->marginLarge}};@endif
}
@endpush

@push('content-block-css-medium')
.tabs-{{ $renderData->block->unique_id }} {
    @if($settings->heightResponsive)height: {{$settings->heightMedium}};@endif
    @if($settings->paddingResponsive)padding: {{$settings->paddingMedium}};@endif
    @if($settings->marginResponsive)margin: {{$settings->marginMedium}};@endif
}
@endpush

@push('content-block-css-small')
.tabs-{{ $renderData->block->unique_id }} {
    @if($settings->heightResponsive)height: {{$settings->heightSmall}};@endif
    @if($settings->paddingResponsive)padding: {{$settings->paddingSmall}};@endif
    @if($settings->marginResponsive)margin: {{$settings->marginSmall}};@endif
}
@endpush

@push('content-block-css-extra-small')
.tabs-{{ $renderData->block->unique_id }} {
    @if($settings->heightResponsive)height: {{$settings->heightExtraSmall}};@endif
    @if($settings->paddingResponsive)padding: {{$settings->paddingExtraSmall}};@endif
    @if($settings->marginResponsive)margin: {{$settings->marginExtraSmall}};@endif
}
@endpush
