@push('content-block-custom-css')
    @if($settings->css){{ $settings->css }}@endif
@endpush

@push('content-block-css')
.columns-{{ $renderData->block->unique_id }} {
    height: {{ $settings->height }};
    padding: {{$settings->padding}};
    margin: {{$settings->margin}};
    @if($settings->backgroundImage)
        background-image: url('{{ $settings->backgroundImage }}');
        background-attachment: {{ $settings->backgroundStyle }};
        background-position: {{ $settings->backgroundPosition }};
        background-repeat: {{ $settings->backgroundRepeat }};
        box-shadow: inset 0 0 0 2000px {{ $settings->backgroundColor }};
    @else
        background-color: {{ $settings->backgroundColor }};
    @endif
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
.columns-{{ $renderData->block->unique_id }} {
    height: {{ $settings->heightLarge }};
    padding: {{ $settings->paddingLarge }};
    margin: {{ $settings->marginLarge }};
    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayLarge == 'flex'))
        flex: {{ $settings->flexLarge }};
        align-self: {{ $settings->alignSelfLarge }};
    @endif
    width: {{ $settings->widthLarge }};
}
@endpush

@push('content-block-css-medium')
.columns-{{ $renderData->block->unique_id }} {
    height: {{ $settings->heightMedium }};
    padding: {{ $settings->paddingMedium }};
    margin: {{ $settings->marginMedium }};
    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayMedium == 'flex'))
        flex: {{ $settings->flexMedium }};
        align-self: {{ $settings->alignSelfMedium }};
    @endif
    width: {{ $settings->widthMedium }};
}
@endpush

@push('content-block-css-small')
.columns-{{ $renderData->block->unique_id }} {
    height: {{ $settings->heightSmall }};
    padding: {{ $settings->paddingSmall }};
    margin: {{ $settings->marginSmall }};
    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displaySmall == 'flex'))
        flex: {{ $settings->flexSmall }};
        align-self: {{ $settings->alignSelfSmall }};
    @endif
    width: {{ $settings->widthSmall }};
}
@endpush

@push('content-block-css-extra-small')
.columns-{{ $renderData->block->unique_id }} {
    height: {{ $settings->heightExtraSmall }};
    padding: {{ $settings->paddingExtraSmall }};
    margin: {{ $settings->marginExtraSmall }};
    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayExtraSmall == 'flex'))
        flex: {{ $settings->flexExtraSmall }};
        align-self: {{ $settings->alignSelfExtraSmall }};
    @endif
    width: {{ $settings->widthExtraSmall }};
}
@endpush
