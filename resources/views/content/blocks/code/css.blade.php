@push('content-block-custom-css')
    {{ $settings->get('css') }}
@endpush

@push('content-block-css')
.code-{{ $block->unique_id }} {
    @if($settings->get('onClick') == 'open-link')cursor: pointer;@endif
    margin: {{ $settings->get('margin') }};
    padding: {{ $settings->get('padding') }};

    @if(isset($parentSettings) && $parentSettings->display == 'flex')
        flex: {{ $settings->get('flex') }};
        width: {{ $settings->get('width') }};
        align-self: {{ $settings->get('alignSelf') }};
    @else
        width: 100%;
    @endif
}
@endpush

@push('content-block-css-large')
.code-{{ $block->unique_id }} {
    padding: {{ $settings->get('paddingLarge') }};
    margin: {{ $settings->get('marginLarge') }};
    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayLarge == 'flex'))
        flex: {{ $settings->get('flexLarge') }};
        align-self: {{ $settings->get('alignSelfLarge') }};
    @endif
    width: {{ $settings->get('widthLarge') }};
}
@endpush

@push('content-block-css-medium')
.code-{{ $block->unique_id }} {
    padding: {{ $settings->get('paddingMedium') }};
    margin: {{ $settings->get('marginMedium') }};
    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayMedium == 'flex'))
        flex: {{ $settings->get('flexMedium') }};
        align-self: {{ $settings->get('alignSelfMedium') }};
    @endif
    width: {{ $settings->get('widthMedium') }};
}
@endpush

@push('content-block-css-small')
.code-{{ $block->unique_id }} {
    padding: {{ $settings->get('paddingSmall') }};
    margin: {{ $settings->get('marginSmall') }};
    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displaySmall == 'flex'))
        flex: {{ $settings->get('flexSmall') }};
        align-self: {{ $settings->get('alignSelfSmall') }};
    @endif
    width: {{ $settings->get('widthSmall') }};
}
@endpush

@push('content-block-css-extra-small')
.code-{{ $block->unique_id }} {
    padding: {{ $settings->get('paddingExtraSmall') }};
    margin: {{ $settings->get('marginExtraSmall') }};
    @if(isset($parentSettings) && ($parentSettings->display == 'flex' || $parentSettings->displayExtraSmall == 'flex'))
        flex: {{ $settings->get('flexExtraSmall') }};
        align-self: {{ $settings->get('alignSelfExtraSmall') }};
    @endif
    width: {{ $settings->get('widthExtraSmall') }};
}
@endpush
