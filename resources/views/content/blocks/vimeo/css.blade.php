@push('content-block-css')
.video-block-{{ $block->unique_id }} {
    margin: {{ $settings->get('margin') }};
    padding: 28.25%;
    position: relative;
    display: block;
    width: 100%;
}
@endpush

@push('content-block-css-large')
.video-block-{{ $block->unique_id }} {
    @if($settings->get('marginResponsive'))margin: {{ $settings->get('marginLarge') }};@endif
}
@endpush

@push('content-block-css-medium')
.video-block-{{ $block->unique_id }} {
    @if($settings->get('marginResponsive'))margin: {{ $settings->get('marginMedium') }};@endif
}
@endpush

@push('content-block-css-small')
.video-block-{{ $block->unique_id }} {
    @if($settings->get('marginResponsive'))margin: {{ $settings->get('marginSmall') }};@endif
}
@endpush

@push('content-block-css-extra-small')
.video-block-{{ $block->unique_id }} {
    @if($settings->get('marginResponsive'))margin: {{ $settings->get('marginExtraSmall') }};@endif
}
@endpush
