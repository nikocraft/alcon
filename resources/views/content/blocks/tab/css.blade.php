@push('content-block-custom-css')
    {{ $settings->get('css') }}
@endpush

@push('content-block-css')
.tab-{{ $column->unique_id }} {
    display: none;
    padding: {{ $settings->get('padding') }};
}
@endpush

@push('content-block-css-large')
.tab-{{ $column->unique_id }} {
    @if($settings->get('paddingResponsive'))padding: {{ $settings->get('paddingLarge') }};@endif
}
@endpush

@push('content-block-css-medium')
.tab-{{ $column->unique_id }} {
    @if($settings->get('paddingResponsive'))padding: {{ $settings->get('paddingMedium') }};@endif
}
@endpush

@push('content-block-css-small')
.tab-{{ $column->unique_id }} {
    @if($settings->get('paddingResponsive'))padding: {{ $settings->get('paddingSmall') }};@endif
}
@endpush

@push('content-block-css-extra-small')
.tab-{{ $column->unique_id }} {
    @if($settings->get('paddingResponsive'))padding: {{ $settings->get('paddingExtraSmall') }};@endif
}
@endpush
