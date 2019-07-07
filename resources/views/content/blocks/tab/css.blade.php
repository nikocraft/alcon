@push('content-block-custom-css')
    @if($settings->css){{ $settings->css }}@endif
@endpush

@push('content-block-css')
.tab-{{ $column->unique_id }} {
    display: none;
    padding: {{$settings->padding}};
}
@endpush

@push('content-block-css-large')
.tab-{{ $column->unique_id }} {
    @if($settings->paddingResponsive)padding: {{$settings->paddingLarge}};@endif
}
@endpush

@push('content-block-css-medium')
.tab-{{ $column->unique_id }} {
    @if($settings->paddingResponsive)padding: {{$settings->paddingMedium}};@endif
}
@endpush

@push('content-block-css-small')
.tab-{{ $column->unique_id }} {
    @if($settings->paddingResponsive)padding: {{$settings->paddingSmall}};@endif
}
@endpush

@push('content-block-css-extra-small')
.tab-{{ $column->unique_id }} {
    @if($settings->paddingResponsive)padding: {{$settings->paddingExtraSmall}};@endif
}
@endpush
