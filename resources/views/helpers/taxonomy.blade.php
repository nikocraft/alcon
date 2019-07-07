@php
    $terms = $post->terms->where('taxonomies.name', $taxonomy);

    if($terms) {
        $termsLinks = [];
        foreach ($terms as $term) {
            array_push($termsLinks, '<a class="post-term" href="/' . lcfirst($taxonomy) . '/' . lcfirst($term->slug) . '">'. ucwords($term->name) .'</a>');
        }

        if(isset($commaSeparate) && $commaSeparate)
            $termsLinks = implode(",&nbsp;", $termsLinks);
        else {
            $termsLinks = implode('', $termsLinks);
        }
    }
@endphp
@if($terms){!! $termsLinks !!}@endif