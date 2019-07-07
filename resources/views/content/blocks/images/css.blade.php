@push('content-block-custom-css')
    @if($settings->css){{ $settings->css }}@endif
@endpush

@push('content-block-css')
    .images-{{ $renderData->block->unique_id }} {
        margin: {{ $settings->margin }};
        padding: {{ $settings->padding }};
        background-color: {{ $settings->backgroundColor }};

        @if(isset($parentSettings) && $parentSettings->display == 'flex')
            flex: {{ $settings->flex }};
            width: {{ $settings->width }};
            align-self: {{ $settings->alignSelf }};
        @else
            width: 100%;
        @endif
    }

    .images-{{ $renderData->block->unique_id }} .image-group {
        margin-left: -{{ $settings->spaceBetweenImages }};
        margin-right: -{{ $settings->spaceBetweenImages }};
    }

    .images-{{ $renderData->block->unique_id }} .image {
        width: {{ $settings->imageWidth }};
        margin: {{ $settings->spaceBetweenImages }};
    }

    .images-{{ $renderData->block->unique_id }} img {
        object-fit: cover;
        height: {{ $settings->imageHeight }};
        width: 100%;
        margin-left: auto;
        margin-right: auto;
        border: {{ $settings->imageBorder }};
        border-radius: {{ $settings->imageBorderRadius }};
    }
    .images-{{ $renderData->block->unique_id }}:hover {
        @if($settings->backgroundColorAdvanced)background-color: {{ $settings->backgroundColorHover }};@endif
    }

    .images-{{ $renderData->block->unique_id }}:active {
        @if($settings->backgroundColorAdvanced)background-color: {{ $settings->backgroundColorActive }};@endif
    }
@endpush

@push('content-block-css-large')
    .images-{{ $renderData->block->unique_id }} {
        padding: {{ $settings->paddingLarge }};
        margin: {{ $settings->marginLarge }};
        @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayLarge == 'flex'))
            flex: {{ $settings->flexLarge }};
            width: {{ $settings->widthLarge }};
            align-self: {{ $settings->alignSelfLarge }};
        @endif
    }
    .images-{{ $renderData->block->unique_id }} img {
        height: {{ $settings->imageHeightLarge }};
    }
@endpush

@push('content-block-css-medium')
    .images-{{ $renderData->block->unique_id }} {
        padding: {{ $settings->paddingMedium }};
        margin: {{ $settings->marginMedium }};

        @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayMedium == 'flex'))
            flex: {{ $settings->flexMedium }};
            width: {{ $settings->widthMedium }};
            align-self: {{ $settings->alignSelfMedium }};
        @endif
    }
    @php
        $space = preg_replace('/\D/', '', $settings->spaceBetweenImages);
        $offset = $space * 2;
    @endphp
    .images-{{ $renderData->block->unique_id }} .image {
        width: calc(50% - {{ $offset }}px);
        margin: {{ $settings->spaceBetweenImages }};
    }
    .images-{{ $renderData->block->unique_id }} img {
        height: {{ $settings->imageHeightMedium }};
    }
@endpush

@push('content-block-css-small')
    .images-{{ $renderData->block->unique_id }} {
        padding: {{ $settings->paddingSmall }};
        margin: {{ $settings->marginSmall }};
        @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displaySmall == 'flex'))
            flex: {{ $settings->flexSmall }};
            width: {{ $settings->widthSmall }};
            align-self: {{ $settings->alignSelfSmall }};
        @endif
    }
    .images-{{ $renderData->block->unique_id }} .image {
        width: 100%;
        margin: {{ $settings->spaceBetweenImages }};
    }
    .images-{{ $renderData->block->unique_id }} img {
        height: {{ $settings->imageHeightSmall }};
    }
@endpush

@push('content-block-css-extra-small')
    .images-{{ $renderData->block->unique_id }} {
        padding: {{ $settings->paddingExtraSmall }};
        margin: {{ $settings->marginExtraSmall }};
        @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayExtraSmall == 'flex'))
            flex: {{ $settings->flexExtraSmall }};
            width: {{ $settings->widthExtraSmall }};
            align-self: {{ $settings->alignSelfExtraSmall }};
        @endif
    }
    .images-{{ $renderData->block->unique_id }} .image {
        width: 100%;
        margin: {{ $settings->spaceBetweenImages }};
    }
    .images-{{ $renderData->block->unique_id }} img {
        height: {{ $settings->imageHeightExtraSmall }};
    }
@endpush
