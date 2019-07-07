@push('content-block-css')
.video-block-{{ $renderData->block->unique_id }} {
    {{-- display: flex;
    justify-content: {{ $settings->position }}; --}}
    padding: {{ $settings->padding }};
    margin: {{ $settings->margin }};
    height: {{ $settings->height }};
    @if($settings->backgroundColor != "")background-color: {{ $settings->backgroundColor }};@endif
    @if(isset($parentSettings) && $parentSettings->display == 'flex')
        flex: {{ $settings->flex }};
        align-self: {{ $settings->alignSelf }};
    @else
        width: 100%;
    @endif
    width: {{ $settings->width }};
}
@endpush

@push('content-block-css-large')
.video-block-{{ $renderData->block->unique_id }} {
    @if($settings->paddingResponsive)padding: {{ $settings->paddingLarge }};@endif
    @if($settings->marginResponsive)margin: {{ $settings->marginLarge }};@endif
    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayLarge == 'flex'))
        flex: {{ $settings->flexLarge }};
        align-self: {{ $settings->alignSelfLarge }};
    @endif
    width: {{ $settings->widthLarge }};
}
@endpush

@push('content-block-css-medium')
.video-block-{{ $renderData->block->unique_id }} {
    @if($settings->paddingResponsive)padding: {{ $settings->paddingMedium }};@endif
    @if($settings->marginResponsive)margin: {{ $settings->marginMedium }};@endif
    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayMedium == 'flex'))
        flex: {{ $settings->flexMedium }};
        align-self: {{ $settings->alignSelfMedium }};
    @endif
    width: {{ $settings->widthMedium }};
}
@endpush

@push('content-block-css-small')
.video-block-{{ $renderData->block->unique_id }} {
    @if($settings->paddingResponsive)padding: {{ $settings->paddingSmall }};@endif
    @if($settings->marginResponsive)margin: {{ $settings->marginSmall }};@endif
    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displaySmall == 'flex'))
        flex: {{ $settings->flexSmall }};
        align-self: {{ $settings->alignSelfSmall }};
    @endif
    width: {{ $settings->widthSmall }};
}
@endpush

@push('content-block-css-extra-small')
.video-block-{{ $renderData->block->unique_id }} {
    @if($settings->paddingResponsive)padding: {{ $settings->paddingExtraSmall }};@endif
    @if($settings->marginResponsive)margin: {{ $settings->marginExtraSmall }};@endif
    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayExtraSmall == 'flex'))
        flex: {{ $settings->flexExtraSmall }};
        align-self: {{ $settings->alignSelfExtraSmall }};
    @endif
    width: {{ $settings->widthExtraSmall }};
}
@endpush
