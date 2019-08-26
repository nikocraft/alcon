@push('content-block-custom-css')
    {{ $settings->get('css') }}
@endpush

@push('content-block-css')
.container-{{ $block->unique_id }} {
    @if($settings->get('onClick') == 'open-link')
    cursor: pointer;
    @endif
    height: {{ $settings->get('height') }};
    padding: {{ $settings->get('padding') }} !important;
    margin: {{ $settings->get('margin') }} !important;
    {{-- overflow-x: {{ $settings->overflowX }};
    overflow-y: {{ $settings->overflowY }}; --}}
    @if($settings->get('display') == 'flex')
        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        flex-direction: {{ $settings->get('flexDirection') }};
        justify-content: {{ $settings->get('justifyContent') }};
        align-items: {{ $settings->get('alignItems') }};
        flex-wrap: {{ $settings->get('flexWrap') }};
        align-content: {{ $settings->get('alignContent') }};
    @else
        display: {{ $settings->get('display') }};
    @endif
    @if($settings->get('backgroundImage'))
        background-image: url('{{ $settings->get('backgroundImage') }}');
        background-attachment: {{ $settings->get('backgroundStyle') }};
        background-position: {{ $settings->get('backgroundPosition') }};
        background-size: {{ $settings->get('backgroundSize') }};
        background-repeat: {{ $settings->get('backgroundRepeat') }};
        {{-- box-shadow: inset 0 0 0 2000px {{$settings->backgroundColor}}; --}}
    @else
        background-color: {{ $settings->get('backgroundColor') }} !important;
    @endif
}
.container-{{ $block->unique_id }}:hover {
    @if($settings->get('backgroundColorAdvanced'))background-color: {{ $settings->get('backgroundColorHover') }} !important;@endif
}
.container-{{ $block->unique_id }}:active {
    @if($settings->get('backgroundColorAdvanced'))background-color: {{ $settings->get('backgroundColorActive') }} !important;@endif
}
.container-{{ $block->unique_id }}:focus {
    @if($settings->get('backgroundColorAdvanced'))background-color: {{ $settings->get('backgroundColorFocus') }} !important;@endif
}
.container .container-{{ $block->unique_id }} {
    width: auto;
}
@endpush

@push('content-block-css-large')
.container-{{ $block->unique_id }} {
    height: {{ $settings->get('heightLarge') }};
    padding: {{ $settings->get('paddingLarge') }} !important;
    margin: {{ $settings->get('marginLarge') }};

    @if($settings->get('displayResponsive'))
        @if($settings->get('displayLarge') == 'flex')
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            flex-direction: {{ $settings->get('flexDirectionLarge') }};
            justify-content: {{ $settings->get('justifyContentLarge') }};
            align-items: {{ $settings->get('alignItemsLarge') }};
            flex-wrap: {{ $settings->get('flexWrapLarge') }};
            align-content: {{ $settings->get('alignContentLarge') }};
        @else
            display: {{$settings->get('displayLarge')}};
        @endif
    @endif
}
@endpush

@push('content-block-css-medium')
.container-{{ $block->unique_id }} {
    height: {{ $settings->get('heightMedium') }};
    padding: {{ $settings->get('paddingMedium') }} !important;
    margin: {{ $settings->get('marginMedium') }};

    @if($settings->get('displayResponsive'))
        @if($settings->get('displayMedium') == 'flex')
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            flex-direction: {{ $settings->get('flexDirectionMedium') }};
            justify-content: {{ $settings->get('justifyContentMedium') }};
            align-items: {{ $settings->get('alignItemsMedium') }};
            flex-wrap: {{ $settings->get('flexWrapMedium') }};
            align-content: {{ $settings->get('alignContentMedium') }};
        @else
            display: {{ $settings->get('displayMedium') }};
        @endif
    @endif
}
@endpush

@push('content-block-css-small')
.container-{{ $block->unique_id }} {
    height: {{ $settings->get('heightSmall') }};
    padding: {{ $settings->get('paddingSmall') }} !important;
    margin: {{ $settings->get('marginSmall') }};

    @if($settings->get('displayResponsive'))
        @if($settings->get('displaySmall') == 'flex')
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            flex-direction: {{ $settings->get('flexDirectionSmall') }};
            justify-content: {{ $settings->get('justifyContentSmall') }};
            align-items: {{ $settings->get('alignItemsSmall') }};
            flex-wrap: {{ $settings->get('flexWrapSmall') }};
            align-content: {{ $settings->get('alignContentSmall') }};
        @else
            display: {{ $settings->get('displaySmall') }};
        @endif
    @endif
}
@endpush

@push('content-block-css-extra-small')
.container-{{ $block->unique_id }} {
    height: {{ $settings->get('heightExtraSmall') }};
    padding: {{ $settings->get('paddingExtraSmall') }} !important;
    margin: {{ $settings->get('marginExtraSmall') }};

    @if($settings->displayResponsive)
        @if($settings->displayExtraSmall == 'flex')
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            flex-direction: {{ $settings->get('flexDirectionExtraSmall') }};
            justify-content: {{ $settings->get('justifyContentExtraSmall') }};
            align-items: {{ $settings->get('alignItemsExtraSmall') }};
            flex-wrap: {{ $settings->get('flexWrapExtraSmall') }};
            align-content: {{ $settings->get('alignContentExtraSmall') }};
        @else
            display: {{ $settings->get('displayExtraSmall') }};
        @endif
    @endif
}
@endpush
