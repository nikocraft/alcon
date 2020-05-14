@push('content-block-custom-css')
    {{ $settings->get('css') }}
@endpush

@push('content-block-css')
.tabs-{{ $block->unique_id }} {
    height: {{ $settings->get('heightLarge') }};
    padding: {{ $settings->get('padding') }};
    margin: {{ $settings->get('margin') }};

    @if($settings->get('tabNavPosition') != 'top')
        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
    @endif
}

.tabs-{{ $block->unique_id }} .tabsbar {
    flex: 0.2;
    margin: {{ $settings->get('tabNavMargin') }};
    @if($settings->get('tabNavPosition') == 'right')
    order: 2;
    @endif
}
@if($settings->get('tabNavPosition') != 'top')
.tabs-{{ $block->unique_id }} .tabsbar .tabsbar-item {
    width: 100%;
    margin-bottom: 5px;
}
@endif

.tabs-{{ $block->unique_id }} .tab-block {
    flex: 0.8;
}
@endpush

@push('content-block-css-large')
.tabs-{{ $block->unique_id }} {
    @if($settings->get('heightResponsive'))height: {{ $settings->get('heightLarge') }};@endif
    @if($settings->get('paddingResponsive'))padding: {{ $settings->get('paddingLarge') }};@endif
    @if($settings->get('marginResponsive'))margin: {{ $settings->get('marginLarge') }};@endif
}
@endpush

@push('content-block-css-medium')
.tabs-{{ $block->unique_id }} {
    @if($settings->get('heightResponsive'))height: {{ $settings->get('heightMedium') }};@endif
    @if($settings->get('paddingResponsive'))padding: {{ $settings->get('paddingMedium') }};@endif
    @if($settings->get('marginResponsive'))margin: {{ $settings->get('marginMedium') }};@endif
}
@endpush

@push('content-block-css-small')
.tabs-{{ $block->unique_id }} {
    @if($settings->get('heightResponsive'))height: {{ $settings->get('heightSmall') }};@endif
    @if($settings->get('paddingResponsive'))padding: {{ $settings->get('paddingSmall') }};@endif
    @if($settings->get('marginResponsive'))margin: {{ $settings->get('marginSmall') }};@endif
}
@endpush

@push('content-block-css-extra-small')
.tabs-{{ $block->unique_id }} {
    @if($settings->get('heightResponsive'))height: {{ $settings->get('heightExtraSmall') }};@endif
    @if($settings->get('paddingResponsive'))padding: {{ $settings->get('paddingExtraSmall') }};@endif
    @if($settings->get('marginResponsive'))margin: {{ $settings->get('marginExtraSmall') }};@endif
}
@endpush
