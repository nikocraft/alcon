@push('content-block-custom-css')
    @if($settings->css){{ $settings->css }}@endif
@endpush

@push('content-block-css')
.slide-{{ $currentBlock->unique_id }} {
    @if($settings->onClick == 'open-link')
    cursor: pointer;
    @endif
    background-image: url("{{ $currentBlock->content }}");
    background-size: cover;
    background-position: center center;
    height: 100%;
    @if($settings->display == 'flex')
        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        flex-direction: {{$settings->flexDirection}};
        justify-content: {{$settings->justifyContent}};
        align-items: {{$settings->alignItems}};
        flex-wrap: {{$settings->flexWrap}};
        align-content: {{$settings->alignContent}};
    @else
        display: {{$settings->display}};
    @endif
}
.slide-{{ $currentBlock->unique_id }} > div, .slide-link {
    @if($settings->display == 'flex')
        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        flex-direction: {{$settings->flexDirection}};
        justify-content: {{$settings->justifyContent}};
        align-items: {{$settings->alignItems}};
        flex-wrap: {{$settings->flexWrap}};
        align-content: {{$settings->alignContent}};
    @else
        display: {{$settings->display}};
    @endif
}
@endpush

@push('content-block-css-large')
.slide-{{ $currentBlock->unique_id }} {
    @if($settings->displayResponsive)
        @if($settings->displayLarge == 'flex')
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            flex-direction: {{$settings->flexDirectionLarge}};
            justify-content: {{$settings->justifyContentLarge}};
            align-items: {{$settings->alignItemsLarge}};
            flex-wrap: {{$settings->flexWrapLarge}};
            align-content: {{$settings->alignContentLarge}};
        @else
            display: {{$settings->displayLarge}};
        @endif
    @endif
}

.slide-{{ $currentBlock->unique_id }} > div {
    @if($settings->displayLarge == 'flex')
        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        flex-direction: {{$settings->flexDirectionLarge}};
        justify-content: {{$settings->justifyContentLarge}};
        align-items: {{$settings->alignItemsLarge}};
        flex-wrap: {{$settings->flexWrapLarge}};
        align-content: {{$settings->alignContentLarge}};
    @else
        display: {{$settings->displayLarge}};
    @endif
}
@endpush

@push('content-block-css-medium')
.slide-{{ $currentBlock->unique_id }} {
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

.slide-{{ $currentBlock->unique_id }} > div {
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
}
@endpush

@push('content-block-css-small')
.slide-{{ $currentBlock->unique_id }} {
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
.slide-{{ $currentBlock->unique_id }} > div {
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
}
@endpush

@push('content-block-css-extra-small')
.slide-{{ $currentBlock->unique_id }} {
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

.slide-{{ $currentBlock->unique_id }} > div {
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
}
@endpush
