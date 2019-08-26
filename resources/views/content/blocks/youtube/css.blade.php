@push('content-block-css')
.video-block-{{ $block->unique_id }} {
    {{-- display: flex;
    justify-content: {{ $settings->position }}; --}}
    padding: {{ $settings->get('padding') }};
    margin: {{ $settings->get('margin') }};
    height: {{ $settings->get('height') }};
    @if($settings->get('backgroundColor') != "")background-color: {{ $settings->get('backgroundColor') }};@endif
    @if(isset($parentSettings) && $parentSettings->get('display') == 'flex')
        flex: {{ $settings->get('flex') }};
        align-self: {{ $settings->get('alignSelf') }};
    @else
        width: 100%;
    @endif
    width: {{ $settings->get('width') }};
}
@endpush

@push('content-block-css-large')
.video-block-{{ $block->unique_id }} {
    @if($settings->get('paddingResponsive'))padding: {{ $settings->get('paddingLarge') }};@endif
    @if($settings->get('marginResponsive'))margin: {{ $settings->get('marginLarge') }};@endif
    @if(isset($parentSettings) && ($parentSettings->get('display') == 'flex' || $parentSettings->get('displayLarge') == 'flex'))
        flex: {{ $settings->get('flexLarge') }};
        align-self: {{ $settings->get('alignSelfLarge') }};
    @endif
    width: {{ $settings->get('widthLarge') }};
}
@endpush

@push('content-block-css-medium')
.video-block-{{ $block->unique_id }} {
    @if($settings->get('paddingResponsive'))padding: {{ $settings->get('paddingMedium') }};@endif
    @if($settings->get('marginResponsive'))margin: {{ $settings->get('marginMedium') }};@endif
    @if(isset($parentSettings) && ($parentSettings->get('display') == 'flex' || $parentSettings->get('displayMedium') == 'flex'))
        flex: {{ $settings->get('flexMedium') }};
        align-self: {{ $settings->get('alignSelfMedium') }};
    @endif
    width: {{ $settings->get('widthMedium') }};
}
@endpush

@push('content-block-css-small')
.video-block-{{ $block->unique_id }} {
    @if($settings->get('paddingResponsive'))padding: {{ $settings->get('paddingSmall') }};@endif
    @if($settings->get('marginResponsive'))margin: {{ $settings->get('marginSmall') }};@endif
    @if(isset($parentSettings) && ($parentSettings->get('display') == 'flex' || $parentSettings->get('displaySmall') == 'flex'))
        flex: {{ $settings->get('flexSmall') }};
        align-self: {{ $settings->get('alignSelfSmall') }};
    @endif
    width: {{ $settings->get('widthSmall') }};
}
@endpush

@push('content-block-css-extra-small')
.video-block-{{ $block->unique_id }} {
    @if($settings->get('paddingResponsive'))padding: {{ $settings->get('paddingExtraSmall') }};@endif
    @if($settings->get('marginResponsive'))margin: {{ $settings->get('marginExtraSmall') }};@endif
    @if(isset($parentSettings) && ($parentSettings->get('display') == 'flex' || $parentSettings->get('displayExtraSmall') == 'flex'))
        flex: {{ $settings->get('flexExtraSmall') }};
        align-self: {{ $settings->get('alignSelfExtraSmall') }};
    @endif
    width: {{ $settings->get('widthExtraSmall') }};
}
@endpush
