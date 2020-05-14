@push('content-block-custom-css')
    @if($settings->css){{ $settings->css }}@endif
@endpush

@push('content-block-css')
.slide-{{ $currentBlock->unique_id }} {
    @if($settings->onClick == 'open-link')
    cursor: pointer;
    @endif
    background-image: url("{{ $currentBlock->content }}");
    background-size: cover;
    background-position: center center;
    height: 100%;
}
@endpush