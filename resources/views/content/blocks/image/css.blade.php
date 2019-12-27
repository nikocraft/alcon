@push('content-block-custom-css')
    {{ $settings->get('css') }}
@endpush

@push('content-block-css')
.image-{{ $block->unique_id }} {
    display: flex;
    justify-content: {{ $settings->get('imagePosition') }};
    align-items: center;
    padding: {{ $settings->get('padding') }};
    margin: {{ $settings->get('margin') }};
    width: {{ $settings->get('width') }};
    background-color: {{ $settings->get('backgroundColor') }};

    @if(isset($parentSettings) && $parentSettings->get('display') == 'flex')
        flex: {{ $settings->get('flex') }};
        width: {{ $settings->get('width') }};
        align-self: {{ $settings->get('alignSelf') }};
    @else
        width: 100%;
    @endif
}

.image-{{ $block->unique_id }} img {
    border: {{ $settings->get('imageBorder') }};
    border-radius: {{ $settings->get('imageBorderRadius') }};
}
@endpush

@push('content-block-css-large')
.image-{{ $block->unique_id }} {
    padding: {{ $settings->get('paddingLarge') }};
    margin: {{ $settings->get('marginLarge') }};

    @if(isset($parentSettings) && ($parentSettings->get('display') == 'flex' || $parentSettings->get('displayLarge') == 'flex'))
        flex: {{ $settings->get('flexLarge') }};
        width: {{ $settings->get('widthLarge') }};
        align-self: {{ $settings->get('alignSelfLarge') }};
    @endif
}
@endpush

@push('content-block-css-medium')
.image-{{ $block->unique_id }} {
    padding: {{ $settings->get('paddingMedium') }};
    margin: {{ $settings->get('marginMedium') }};

    @if(isset($parentSettings) && ($parentSettings->get('display') == 'flex' || $parentSettings->get('displayMedium') == 'flex'))
        flex: {{ $settings->get('flexMedium') }};
        width: {{ $settings->get('widthMedium') }};
        align-self: {{ $settings->get('alignSelfMedium') }};
    @endif
}
@endpush

@push('content-block-css-small')
.image-{{ $block->unique_id }} {
    padding: {{ $settings->get('paddingSmall') }};
    margin: {{ $settings->get('marginSmall') }};

    @if(isset($parentSettings) && ($parentSettings->get('display') == 'flex' || $parentSettings->get('displaySmall') == 'flex'))
        flex: {{ $settings->get('flexSmall') }};
        width: {{ $settings->get('widthSmall') }};
        align-self: {{ $settings->get('alignSelfSmall') }};
    @endif
}
@endpush

@push('content-block-css-extra-small')
.image-{{ $block->unique_id }} {
    padding: {{ $settings->get('paddingExtraSmall') }};
    margin: {{ $settings->get('marginExtraSmall') }};

    @if(isset($parentSettings) && ($parentSettings->get('display') == 'flex' || $parentSettings->get('displayExtraSmall') == 'flex'))
        flex: {{ $settings->get('flexExtraSmall') }};
        width: {{ $settings->get('widthExtraSmall') }};
        align-self: {{ $settings->get('alignSelfExtraSmall') }};
    @endif
}
@endpush
