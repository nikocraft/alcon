@push('content-block-custom-css')
    @if($settings->get('css')){{ $settings->get('css') }}@endif
@endpush

@push('content-block-css')
    .images-{{ $block->unique_id }} {
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

    .images-{{ $block->unique_id }} .image-group {
        margin-left: -{{ $settings->get('spaceBetweenImages') }};
        margin-right: -{{ $settings->get('spaceBetweenImages') }};
    }

    .images-{{ $block->unique_id }} .image {
        width: {{ $settings->get('imageWidth') }};
        margin: {{ $settings->get('spaceBetweenImages') }};
    }

    .images-{{ $block->unique_id }} img {
        object-fit: cover;
        height: {{ $settings->get('imageHeight') }};
        width: 100%;
        margin-left: auto;
        margin-right: auto;
        border: {{ $settings->get('imageBorder') }};
        border-radius: {{ $settings->get('imageBorderRadius') }};
    }
    .images-{{ $block->unique_id }}:hover {
        @if($settings->get('backgroundColorAdvanced'))background-color: {{ $settings->get('backgroundColorHover') }};@endif
    }

    .images-{{ $block->unique_id }}:active {
        @if($settings->get('backgroundColorAdvanced'))background-color: {{ $settings->get('backgroundColorActive') }};@endif
    }
@endpush

@push('content-block-css-large')
    .images-{{ $block->unique_id }} {
        padding: {{ $settings->get('paddingLarge') }};
        margin: {{ $settings->get('marginLarge') }};
        @if(isset($parentSettings) && ($parentSettings->get('display') == 'flex' || $parentSettings->get('displayLarge') == 'flex'))
            flex: {{ $settings->get('flexLarge') }};
            width: {{ $settings->get('widthLarge') }};
            align-self: {{ $settings->get('alignSelfLarge') }};
        @endif
    }
    .images-{{ $block->unique_id }} img {
        height: {{ $settings->get('imageHeightLarge') }};
    }
@endpush

@push('content-block-css-medium')
    .images-{{ $block->unique_id }} {
        padding: {{ $settings->get('paddingMedium') }};
        margin: {{ $settings->get('marginMedium') }};

        @if(isset($parentSettings) && ($parentSettings->get('display') == 'flex' || $parentSettings->get('displayMedium') == 'flex'))
            flex: {{ $settings->get('flexMedium') }};
            width: {{ $settings->get('widthMedium') }};
            align-self: {{ $settings->get('alignSelfMedium') }};
        @endif
    }
    @php
        $space = preg_replace('/\D/', '', $settings->get('spaceBetweenImages'));
        $offset = $space * 2;
    @endphp
    .images-{{ $block->unique_id }} .image {
        width: calc(50% - {{ $offset }}px);
        margin: {{ $settings->get('spaceBetweenImages') }};
    }
    .images-{{ $block->unique_id }} img {
        height: {{ $settings->get('imageHeightMedium') }};
    }
@endpush

@push('content-block-css-small')
    .images-{{ $block->unique_id }} {
        padding: {{ $settings->get('paddingSmall') }};
        margin: {{ $settings->get('marginSmall') }};
        @if(isset($parentSettings) && ($parentSettings->get('display') == 'flex' || $parentSettings->get('displaySmall') == 'flex'))
            flex: {{ $settings->get('flexSmall') }};
            width: {{ $settings->get('widthSmall') }};
            align-self: {{ $settings->get('alignSelfSmall') }};
        @endif
    }
    .images-{{ $block->unique_id }} .image {
        width: 100%;
        margin: {{ $settings->get('spaceBetweenImages') }};
    }
    .images-{{ $block->unique_id }} img {
        height: {{ $settings->get('imageHeightSmall') }};
    }
@endpush

@push('content-block-css-extra-small')
    .images-{{ $block->unique_id }} {
        padding: {{ $settings->get('paddingExtraSmall') }};
        margin: {{ $settings->get('marginExtraSmall') }};
        @if(isset($parentSettings) && ($parentSettings->get('display') == 'flex' || $parentSettings->get('displayExtraSmall') == 'flex'))
            flex: {{ $settings->get('flexExtraSmall') }};
            width: {{ $settings->get('widthExtraSmall') }};
            align-self: {{ $settings->get('alignSelfExtraSmall') }};
        @endif
    }
    .images-{{ $block->unique_id }} .image {
        width: 100%;
        margin: {{ $settings->get('spaceBetweenImages') }};
    }
    .images-{{ $block->unique_id }} img {
        height: {{ $settings->get('imageHeightExtraSmall') }};
    }
@endpush
