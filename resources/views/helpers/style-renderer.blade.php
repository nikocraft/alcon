@php
    if(!isset($template)) {
        $template = null;
    }
@endphp
@isset($value)
    @if(is_array($value))
        @foreach($value as $size => $responsiveValue)
            @push('theme-css-'.$size)
                @isset($selector)
                    {{ $selector }} { {{ $property }}: @css_value(array_merge(compact(['property', 'template']), ['value' => $responsiveValue])); }
                @endisset
            @endpush
        @endforeach
    @else
        {{ $property }}: @css_value(compact(['property', 'value', 'template']));
    @endif
@endisset