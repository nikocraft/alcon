@php
    $output = '';
    $render = isset($render) ? $render : true;
    $important = isset($important) ? $important : false;

    if($render) {
        switch ($property) {
            case 'text-shadow':
                if($value != '0px 0px 0px transparent' && $value != '') {
                    $output = $property. ':' . $value;
                    $output = $important ? $output . ' !important' : $output;
                    $output = $output . ';';
                }
                break;

            case 'background-image':
                if($value != '') {
                    $output = $property. ': url(' . $value . ')';
                    $output = $important ? $output . ' !important' : $output;
                    $output = $output . ';';
                }
                break;
            
            default:
                if($value != 'default' && $value != '') {
                    $output = $property. ':' . $value;
                    $output = $important ? $output . ' !important' : $output;
                    $output = $output . ';';
                }
                break;
        }
        echo $output;
    }
@endphp