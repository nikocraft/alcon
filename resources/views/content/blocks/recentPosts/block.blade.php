@php
    $block = $renderData->block;
    $settings = $block->settings;
    $posts = Content::where('content_type_id', $settings->get('postsType'))->latest()->take($settings->get('numberOfPosts'))->get();
@endphp

@foreach ($posts as $post)
    @if($settings->get('renderFeaturedImage'))
        <div class="post">
            @if($post->featuredimage)
                <div class="post-featured-image">
                    <a href="/{{ $post->type->slug }}/{{ $post->slug }}">
                        <div class="background-image" style="background-image: url('{{ $post->featuredimage->medium }}');"></div>
                    </a>
                </div>
            @else
                <div class="post-featured-image">
                    <a href="/{{ $post->type->slug }}/{{ $post->slug }}">
                        <div class="no-background-image"></div>
                    </a>
                </div>
            @endif
            <div class="post-details">
                <div class="post-title">
                    <a class="post-title-link" href="/{{ $post->type->slug }}/{{ $post->slug }}">
                        {{ mb_strimwidth(strip_tags($post->title), 0, 65, '...')  }}
                    </a>
                </div>
                <div class="post-date">{{ $post->created_at->format('Y-m-d') }}</div>
            </div>
        </div>
    @else 
        <div style="border-bottom: 1px dotted #d6d6d6; padding: 7px 0px;"><a style="color: #777;" href="/{{ $post->type->slug }}/{{ $post->slug }}">{{ $post->title }}</a></div>
    @endif
@endforeach
