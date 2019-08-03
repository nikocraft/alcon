@push('content-block-custom-css')
    @if($settings->get('css')){{ $settings->get('css') }}@endif
@endpush

@push('content-block-css')
    .images-{{ $renderData->block->unique_id }} {
        margin: {{ $settings->get('margin') }};
        padding: {{ $settings->get('padding') }};
        background-color: {{ $settings->get('backgroundColor') }};

        @if(isset($parentSettings) && $parentSettings->display == 'flex')
            flex: {{ $settings->get('flex') }};
            width: {{ $settings->get('width') }};
            align-self: {{ $settings->get('alignSelf') }};
        @else
            width: 100%;
        @endif
    }

    .images-{{ $renderData->block->unique_id }} .image-group {
        margin-left: -{{ $settings->get('spaceBetweenImages') }};
        margin-right: -{{ $settings->get('spaceBetweenImages') }};
    }

    .images-{{ $renderData->block->unique_id }} .image {
        width: {{ $settings->get('imageWidth') }};
        margin: {{ $settings->get('spaceBetweenImages') }};
    }

    .images-{{ $renderData->block->unique_id }} img {
        object-fit: cover;
        height: {{ $settings->get('imageHeight') }};
        width: 100%;
        margin-left: auto;
        margin-right: auto;
        border: {{ $settings->get('imageBorder') }};
        border-radius: {{ $settings->get('imageBorderRadius') }};
    }
    .images-{{ $renderData->block->unique_id }}:hover {
        @if($settings->get('backgroundColorAdvanced'))background-color: {{ $settings->get('backgroundColorHover') }};@endif
    }

    .images-{{ $renderData->block->unique_id }}:active {
        @if($settings->get('backgroundColorAdvanced'))background-color: {{ $settings->get('backgroundColorActive') }};@endif
    }
@endpush

@push('content-block-css-large')
    .images-{{ $renderData->block->unique_id }} {
        padding: {{ $settings->get('paddingLarge') }};
        margin: {{ $settings->get('marginLarge') }};
        @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayLarge == 'flex'))
            flex: {{ $settings->get('flexLarge') }};
            width: {{ $settings->get('widthLarge') }};
            align-self: {{ $settings->get('alignSelfLarge') }};
        @endif
    }
    .images-{{ $renderData->block->unique_id }} img {
        height: {{ $settings->get('imageHeightLarge') }};
    }
@endpush

@push('content-block-css-medium')
    .images-{{ $renderData->block->unique_id }} {
        padding: {{ $settings->get('paddingMedium') }};
        margin: {{ $settings->get('marginMedium') }};

        @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayMedium == 'flex'))
            flex: {{ $settings->get('flexMedium') }};
            width: {{ $settings->get('widthMedium') }};
            align-self: {{ $settings->get('alignSelfMedium') }};
        @endif
    }
    @php
        $space = preg_replace('/\D/', '', $settings->get('spaceBetweenImages'));
        $offset = $space * 2;
    @endphp
    .images-{{ $renderData->block->unique_id }} .image {
        width: calc(50% - {{ $offset }}px);
        margin: {{ $settings->get('spaceBetweenImages') }};
    }
    .images-{{ $renderData->block->unique_id }} img {
        height: {{ $settings->get('imageHeightMedium') }};
    }
@endpush

@push('content-block-css-small')
    .images-{{ $renderData->block->unique_id }} {
        padding: {{ $settings->get('paddingSmall') }};
        margin: {{ $settings->get('marginSmall') }};
        @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displaySmall == 'flex'))
            flex: {{ $settings->get('flexSmall') }};
            width: {{ $settings->get('widthSmall') }};
            align-self: {{ $settings->get('alignSelfSmall') }};
        @endif
    }
    .images-{{ $renderData->block->unique_id }} .image {
        width: 100%;
        margin: {{ $settings->get('spaceBetweenImages') }};
    }
    .images-{{ $renderData->block->unique_id }} img {
        height: {{ $settings->get('imageHeightSmall') }};
    }
@endpush

@push('content-block-css-extra-small')
    .images-{{ $renderData->block->unique_id }} {
        padding: {{ $settings->get('paddingExtraSmall') }};
        margin: {{ $settings->get('marginExtraSmall') }};
        @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayExtraSmall == 'flex'))
            flex: {{ $settings->get('flexExtraSmall') }};
            width: {{ $settings->get('widthExtraSmall') }};
            align-self: {{ $settings->get('alignSelfExtraSmall') }};
        @endif
    }
    .images-{{ $renderData->block->unique_id }} .image {
        width: 100%;
        margin: {{ $settings->get('spaceBetweenImages') }};
    }
    .images-{{ $renderData->block->unique_id }} img {
        height: {{ $settings->get('imageHeightExtraSmall') }};
    }
@endpush
