@push('content-block-custom-css')
    @if($settings->get('css')){{ $settings->get('css') }}@endif
@endpush

@push('content-block-css')
    .images-block-{{ $block->unique_id }} {
        @cssproperty([ 'property' => 'padding', 'value' => $settings->get('padding') ]) @endcssproperty
        @cssproperty([ 'property' => 'margin', 'value' => $settings->get('margin') ]) @endcssproperty
        @cssproperty([ 'property' => 'background-color', 'value' => $settings->get('backgroundColor') ]) @endcssproperty
    }

    .images-block-{{ $block->unique_id }} .image-wrapper {
        margin-left: -{{ $settings->get('spaceBetweenImages') }};
        margin-right: -{{ $settings->get('spaceBetweenImages') }};
    }

    .images-block-{{ $block->unique_id }} .images-block-image {
        @cssproperty([ 'property' => 'width', 'value' => $settings->get('imageWidth') ]) @endcssproperty
        @cssproperty([ 'property' => 'margin', 'value' => $settings->get('spaceBetweenImages') ]) @endcssproperty
    }

    .images-block-{{ $block->unique_id }} img {
        object-fit: cover;
        width: 100%;
        margin-left: auto;
        margin-right: auto;
        @cssproperty([ 'property' => 'height', 'value' => $settings->get('imageHeight') ]) @endcssproperty
        @cssproperty([ 'property' => 'border', 'value' => $settings->get('imageBorder') ]) @endcssproperty
        @cssproperty([ 'property' => 'border-radius', 'value' => $settings->get('imageBorderRadius') ]) @endcssproperty
    }
@endpush

@push('content-block-css-large')
    .images-block-{{ $block->unique_id }} {
        @cssproperty([ 'property' => 'padding', 'value' => $settings->get('paddingLarge')]) @endcssproperty
        @cssproperty([ 'property' => 'margin', 'value' => $settings->get('marginLarge') ]) @endcssproperty
    }
    .images-block-{{ $block->unique_id }} img {
        @cssproperty([ 'property' => 'height', 'value' => $settings->get('imageHeightLarge') ]) @endcssproperty
    }
@endpush

@push('content-block-css-medium')
    .images-block-{{ $block->unique_id }} {
        @cssproperty([ 'property' => 'padding', 'value' => $settings->get('paddingMedium')]) @endcssproperty
        @cssproperty([ 'property' => 'margin', 'value' => $settings->get('marginMedium') ]) @endcssproperty
    }
    @php
        $space = preg_replace('/\D/', '', $settings->get('spaceBetweenImages'));
        $offset = $space * 2;
    @endphp
    .images-block-{{ $block->unique_id }} .images-block-image {
        width: calc(50% - {{ $offset }}px);
        @cssproperty([ 'property' => 'margin', 'value' => $settings->get('spaceBetweenImages') ]) @endcssproperty
    }
    .images-block-{{ $block->unique_id }} img {
        @cssproperty([ 'property' => 'height', 'value' => $settings->get('imageHeightMedium')]) @endcssproperty
    }
@endpush

@push('content-block-css-small')
    .images-block-{{ $block->unique_id }} {
        @cssproperty([ 'property' => 'padding', 'value' => $settings->get('paddingSmall')]) @endcssproperty
        @cssproperty([ 'property' => 'margin', 'value' => $settings->get('marginSmall') ]) @endcssproperty
    }
    .images-block-{{ $block->unique_id }} .images-block-image {
        width: 100%;
        @cssproperty([ 'property' => 'margin', 'value' => $settings->get('spaceBetweenImages') ]) @endcssproperty
    }
    .images-block-{{ $block->unique_id }} img {
        @cssproperty([ 'property' => 'height', 'value' => $settings->get('imageHeightSmall')]) @endcssproperty
    }
@endpush

@push('content-block-css-extra-small')
    .images-block-{{ $block->unique_id }} {
        @cssproperty([ 'property' => 'padding', 'value' => $settings->get('paddingExtraSmall')]) @endcssproperty
        @cssproperty([ 'property' => 'margin', 'value' => $settings->get('marginExtraSmall') ]) @endcssproperty
    }
    .images-block-{{ $block->unique_id }} .images-block-image {
        width: 100%;
        @cssproperty([ 'property' => 'margin', 'value' => $settings->get('spaceBetweenImages') ]) @endcssproperty
    }
    .images-block-{{ $block->unique_id }} img {
        @cssproperty([ 'property' => 'height', 'value' => $settings->get('imageHeightExtraSmall') ]) @endcssproperty
    }
@endpush
