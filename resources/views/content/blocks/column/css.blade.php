@push('content-block-custom-css')
    {{ $settings->get('css') }}
@endpush

@push('content-block-css')
.column-{{ $column->unique_id }} {
    @if($settings->get('onClick') == 'open-link')
    cursor: pointer;
    @endif
    height: {{ $settings->get('height') }};
    padding: {{ $settings->get('padding') }};
    width: {{ $settings->get('width') }};
    margin: 0 {{ $parentSettings->get('columnSpacing') }};
    @if($settings->get('backgroundImage'))
        background-image: url('{{ $settings->get('backgroundImage') }}');
        background-attachment: {{ $settings->get('backgroundStyle') }};
        background-position: {{ $settings->get('backgroundPosition') }};
        background-size: {{ $settings->get('backgroundSize') }};
        background-repeat: {{ $settings->get('backgroundRepeat') }};
        box-shadow: inset 0 0 0 2000px {{ $settings->get('backgroundColor') }};
    @else
        background-color: {{ $settings->get('backgroundColor') }};
    @endif

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
}
@endpush

@push('content-block-css-large')
.column-{{ $column->unique_id }} {
    height: {{ $settings->get('heightLarge') }};
    padding: {{ $settings->get('paddingLarge') }};
    margin: {{ $settings->get('marginLarge') }};

    @if($settings->displayResponsive)
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
.column-{{ $column->unique_id }} {
    height: {{ $settings->get('heightMedium') }};
    padding: {{ $settings->get('paddingMedium') }};
    margin: {{ $settings->get('marginMedium') }};

    @if($settings->displayResponsive)
        @if($settings->displayMedium == 'flex')
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            flex-direction: {{$settings->flexDirectionMedium}};
            justify-content: {{$settings->justifyContentMedium}};
            align-items: {{$settings->alignItemsMedium}};
            flex-wrap: {{$settings->flexWrapMedium}};
            align-content: {{$settings->alignContentMedium}};
        @else
            display: {{$settings->displayMedium}};
        @endif
    @endif
}
@endpush

@push('content-block-css-small')
.column-{{ $column->unique_id }} {
    height: {{ $settings->heightSmall }};
    padding: {{ $settings->paddingSmall }};
    margin: {{ $settings->marginSmall }};

    @if($settings->displayResponsive)
        @if($settings->displaySmall == 'flex')
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            flex-direction: {{$settings->flexDirectionSmall}};
            justify-content: {{$settings->justifyContentSmall}};
            align-items: {{$settings->alignItemsSmall}};
            flex-wrap: {{$settings->flexWrapSmall}};
            align-content: {{$settings->alignContentSmall}};
        @else
            display: {{$settings->displaySmall}};
        @endif
    @endif
}
@endpush

@push('content-block-css-extra-small')
.column-{{ $column->unique_id }} {
    height: {{ $settings->heightExtraSmall }};
    padding: {{ $settings->paddingExtraSmall }};
    margin: {{ $settings->marginExtraSmall }};

    @if($settings->displayResponsive)
        @if($settings->displayExtraSmall == 'flex')
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            flex-direction: {{$settings->flexDirectionExtraSmall}};
            justify-content: {{$settings->justifyContentExtraSmall}};
            align-items: {{$settings->alignItemsExtraSmall}};
            flex-wrap: {{$settings->flexWrapExtraSmall}};
            align-content: {{$settings->alignContentExtraSmall}};
        @else
            display: {{$settings->displayExtraSmall}};
        @endif
    @endif
}
@endpush
