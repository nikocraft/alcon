@push('content-block-css')
.vimeo-block-{{ $block->unique_id }} {
    @cssproperty([ 'property' => 'margin', 'value' => $settings->get('margin') ]) @endcssproperty
    padding: 28.25%;
    position: relative;
    display: block;
    width: 100%;
}
@endpush

@push('content-block-css-large')
.vimeo-block-{{ $block->unique_id }} {
    @cssproperty([ 'render' => $settings->get('marginResponsive'), 'property' => 'margin', 'value' => $settings->get('marginLarge') ]) @endcssproperty
}
@endpush

@push('content-block-css-medium')
.vimeo-block-{{ $block->unique_id }} {
    @cssproperty([ 'render' => $settings->get('marginResponsive'), 'property' => 'margin', 'value' => $settings->get('marginMedium') ]) @endcssproperty
}
@endpush

@push('content-block-css-small')
.vimeo-block-{{ $block->unique_id }} {
    @cssproperty([ 'render' => $settings->get('marginResponsive'), 'property' => 'margin', 'value' => $settings->get('marginSmall') ]) @endcssproperty
}
@endpush

@push('content-block-css-extra-small')
.vimeo-block-{{ $block->unique_id }} {
    @cssproperty([ 'render' => $settings->get('marginResponsive'), 'property' => 'margin', 'value' => $settings->get('marginExtraSmall') ]) @endcssproperty
}
@endpush
