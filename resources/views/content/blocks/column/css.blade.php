@push('content-block-custom-css')
    {{ $settings->get('css') }}
@endpush

@push('content-block-css')
.column-{{ $column->unique_id }} {
    @cssproperty([ 'property' => 'padding', 'value' => $settings->get('padding') ]) @endcssproperty
    @cssproperty([ 'property' => 'margin', 'value' => $settings->get('margin') ]) @endcssproperty
    @cssproperty([ 'property' => 'width', 'value' => $settings->get('width') ]) @endcssproperty
    @cssproperty([ 'property' => 'background-color', 'value' => $settings->get('backgroundColor') ]) @endcssproperty
}
@endpush

@push('content-block-css-large')
.column-{{ $column->unique_id }} {
    @cssproperty([ 'property' => 'padding', 'value' => $settings->get('paddingLarge') ]) @endcssproperty
    @cssproperty([ 'property' => 'margin', 'value' => $settings->get('marginLarge') ]) @endcssproperty
}
@endpush

@push('content-block-css-medium')
.column-{{ $column->unique_id }} {
    @cssproperty([ 'property' => 'padding', 'value' => $settings->get('paddingMedium') ]) @endcssproperty
    @cssproperty([ 'property' => 'margin', 'value' => $settings->get('marginMedium') ]) @endcssproperty
}
@endpush

@push('content-block-css-small')
.column-{{ $column->unique_id }} {
    @cssproperty([ 'property' => 'padding', 'value' => $settings->get('paddingSmall') ]) @endcssproperty
    @cssproperty([ 'property' => 'margin', 'value' => $settings->get('marginSmall') ]) @endcssproperty
}
@endpush

@push('content-block-css-extra-small')
.column-{{ $column->unique_id }} {
    @cssproperty([ 'property' => 'padding', 'value' => $settings->get('paddingExtraSmall') ]) @endcssproperty
    @cssproperty([ 'property' => 'margin', 'value' => $settings->get('marginExtraSmall') ]) @endcssproperty
}
@endpush
