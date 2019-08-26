@push('content-block-custom-css')
    {{ $settings->get('css') }}
@endpush

@push('content-block-css')
.columns-{{ $block->unique_id }} {
    height: {{ $settings->get('height') }};
    padding: {{$settings->get('padding')}};
    margin: {{$settings->get('margin')}};
    @if($settings->backgroundImage)
        background-image: url('{{ $settings->get('backgroundImage') }}');
        background-attachment: {{ $settings->get('backgroundStyle') }};
        background-position: {{ $settings->get('backgroundPosition') }};
        background-repeat: {{ $settings->get('backgroundRepeat') }};
        box-shadow: inset 0 0 0 2000px {{ $settings->get('backgroundColor') }};
    @else
        background-color: {{ $settings->get('backgroundColor') }};
    @endif
    @if(isset($parentSettings) && $parentSettings->get('display') == 'flex')
        flex: {{ $settings->get('flex') }};
        width: {{ $settings->get('width') }};
        align-self: {{ $settings->get('alignSelf') }};
    @else
        width: 100%;
    @endif
}
@endpush

@push('content-block-css-large')
.columns-{{ $block->unique_id }} {
    height: {{ $settings->get('heightLarge') }};
    padding: {{ $settings->get('paddingLarge') }};
    margin: {{ $settings->get('marginLarge') }};
    @if(isset($parentSettings) && ($parentSettings->get('display') == 'flex' || $parentSettings->get('displayLarge') == 'flex'))
        flex: {{ $settings->get('flexLarge') }};
        align-self: {{ $settings->get('alignSelfLarge') }};
    @endif
    width: {{ $settings->get('widthLarge') }};
}
@endpush

@push('content-block-css-medium')
.columns-{{ $block->unique_id }} {
    height: {{ $settings->get('heightMedium') }};
    padding: {{ $settings->get('paddingMedium') }};
    margin: {{ $settings->get('marginMedium') }};
    @if(isset($parentSettings) && ($parentSettings->get('display') == 'flex' || $parentSettings->get('displayMedium') == 'flex'))
        flex: {{ $settings->get('flexMedium') }};
        align-self: {{ $settings->get('alignSelfMedium') }};
    @endif
    width: {{ $settings->get('widthMedium') }};
}
@endpush

@push('content-block-css-small')
.columns-{{ $block->unique_id }} {
    height: {{ $settings->get('heightSmall') }};
    padding: {{ $settings->get('paddingSmall') }};
    margin: {{ $settings->get('marginSmall') }};
    @if(isset($parentSettings) && ($parentSettings->get('display') == 'flex' || $parentSettings->get('displaySmall') == 'flex'))
        flex: {{ $settings->get('flexSmall') }};
        align-self: {{ $settings->get('alignSelfSmall') }};
    @endif
    width: {{ $settings->get('widthSmall') }};
}
@endpush

@push('content-block-css-extra-small')
.columns-{{ $block->unique_id }} {
    height: {{ $settings->get('heightExtraSmall') }};
    padding: {{ $settings->get('paddingExtraSmall') }};
    margin: {{ $settings->get('marginExtraSmall') }};
    @if(isset($parentSettings) && ($parentSettings->get('display') == 'flex' || $parentSettings->get('displayExtraSmall') == 'flex'))
        flex: {{ $settings->get('flexExtraSmall') }};
        align-self: {{ $settings->get('alignSelfExtraSmall') }};
    @endif
    width: {{ $settings->get('widthExtraSmall') }};
}
@endpush
