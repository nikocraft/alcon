@push('content-block-custom-css')
    @if($settings->css){{ $settings->css }}@endif
@endpush

@push('content-block-css')
.slider-{{ $block->unique_id }} {
    height: {{ $settings->height }};
    margin: {{ $settings->margin }};
}
@endpush

@push('content-block-css-large')
.slider-{{ $block->unique_id }} {
    @if($settings->heightResponsive)height: {{$settings->heightLarge}};@endif
    @if($settings->marginResponsive)margin: {{$settings->marginLarge}};@endif
}
@endpush

@push('content-block-css-medium')
.slider-{{ $block->unique_id }} {
    @if($settings->heightResponsive)height: {{$settings->heightMedium}};@endif
    @if($settings->marginResponsive)margin: {{$settings->marginMedium}};@endif
}
@endpush

@push('content-block-css-small')
.slider-{{ $block->unique_id }} {
    @if($settings->heightResponsive)height: {{$settings->heightSmall}};@endif
    @if($settings->marginResponsive)margin: {{$settings->marginSmall}};@endif
}
@endpush

@push('content-block-css-extra-small')
.slider-{{ $block->unique_id }} {
    @if($settings->heightResponsive)height: {{$settings->heightExtraSmall}};@endif
    @if($settings->marginResponsive)margin: {{$settings->marginExtraSmall}};@endif
}
@endpush
