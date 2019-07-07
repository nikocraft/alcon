@php
    $output = '';
    
    if(!isset($template)) {
        $template = null;

        // Predefined templates for css property's value
        switch ($property) {
            case 'background-image':
                $template = 'url("{{ $value }}")';
                break;
        }
    }

    $keysForReplace = ['value', 'val'];
    $output = $template 
        ? preg_replace('/\{\{\s*\$('.implode('|', $keysForReplace) .')\s*\}\}/iu', $value, $template)
        : $value;

    echo $output;
@endphp