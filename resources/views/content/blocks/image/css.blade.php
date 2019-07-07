@push('content-block-custom-css')
    @if($settings->css){{ $settings->css }}@endif
@endpush

@push('content-block-css')
.image-{{ $renderData->block->unique_id }} {
    display: flex;
    justify-content: {{ $settings->imagePosition }};
    align-items: center;
    padding: {{ $settings->padding }};
    margin: {{ $settings->margin }};
    width: {{ $settings->width }};
    background-color: {{ $settings->backgroundColor }};

    @if(isset($parentSettings) && $parentSettings->display == 'flex')
        flex: {{ $settings->flex }};
        width: {{ $settings->width }};
        align-self: {{ $settings->alignSelf }};
    @else
        width: 100%;
    @endif
}
@endpush

@push('content-block-css-large')
.image-{{ $renderData->block->unique_id }} {
    padding: {{ $settings->paddingLarge }};
    margin: {{ $settings->marginLarge }};

    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayLarge == 'flex'))
        flex: {{ $settings->flexLarge }};
        width: {{ $settings->widthLarge }};
        align-self: {{ $settings->alignSelfLarge }};
    @endif
}
@endpush

@push('content-block-css-medium')
.image-{{ $renderData->block->unique_id }} {
    padding: {{ $settings->paddingMedium }};
    margin: {{ $settings->marginMedium }};

    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayMedium == 'flex'))
        flex: {{ $settings->flexMedium }};
        width: {{ $settings->widthMedium }};
        align-self: {{ $settings->alignSelfMedium }};
    @endif
}
@endpush

@push('content-block-css-small')
.image-{{ $renderData->block->unique_id }} {
    padding: {{ $settings->paddingSmall }};
    margin: {{ $settings->marginSmall }};

    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displaySmall == 'flex'))
        flex: {{ $settings->flexSmall }};
        width: {{ $settings->widthSmall }};
        align-self: {{ $settings->alignSelfSmall }};
    @endif
}
@endpush

@push('content-block-css-extra-small')
.image-{{ $renderData->block->unique_id }} {
    padding: {{ $settings->paddingExtraSmall }};
    margin: {{ $settings->marginExtraSmall }};

    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayExtraSmall == 'flex'))
        flex: {{ $settings->flexExtraSmall }};
        width: {{ $settings->widthExtraSmall }};
        align-self: {{ $settings->alignSelfExtraSmall }};
    @endif
}
@endpush
