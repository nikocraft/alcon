@push('content-block-css')
.youtube-block-{{ $block->unique_id }} {
    padding: 28.25%;
    position: relative;
    display: block;
    width: 100%;
    @cssproperty([ 'property' => 'margin', 'value' => $settings->get('margin') ]) @endcssproperty
}
@endpush

@push('content-block-css-large')
.youtube-block-{{ $block->unique_id }} {
    @cssproperty([ 'render' => $settings->get('marginResponsive'), 'property' => 'margin', 'value' => $settings->get('marginLarge') ]) @endcssproperty
}
@endpush

@push('content-block-css-medium')
.youtube-block-{{ $block->unique_id }} {
    @cssproperty([ 'render' => $settings->get('marginResponsive'), 'property' => 'margin', 'value' => $settings->get('marginMedium') ]) @endcssproperty
}
@endpush

@push('content-block-css-small')
.youtube-block-{{ $block->unique_id }} {
    @cssproperty([ 'render' => $settings->get('marginResponsive'), 'property' => 'margin', 'value' => $settings->get('marginSmall') ]) @endcssproperty
}
@endpush

@push('content-block-css-extra-small')
.youtube-block-{{ $block->unique_id }} {
    @cssproperty([ 'render' => $settings->get('marginResponsive'), 'property' => 'margin', 'value' => $settings->get('marginExtraSmall') ]) @endcssproperty
}
@endpush
