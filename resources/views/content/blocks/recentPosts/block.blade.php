@php
    $block = $renderData->block;
    $settings = $block->settings;
    $posts = Content::where('content_type_id', $settings->get('postsType'))->latest()->take($settings->get('numberOfPosts'))->get();
@endphp

@includeIf('content.blocks.recentPosts.css')

<div class="recent-posts-block-{{ $block->unique_id }}">
@foreach ($posts as $post)
    @if($settings->get('renderFeaturedImage'))
        <div class="recent-post">
            @if($post->featuredimage)
                <div class="recent-post-featured-image">
                    <a href="/{{ $post->type->slug }}/{{ $post->slug }}">
                        <div class="background-image" style="background-image: url('{{ $post->featuredimage->medium }}');"></div>
                    </a>
                </div>
            @else
                <div class="recent-post-featured-image">
                    <a href="/{{ $post->type->slug }}/{{ $post->slug }}">
                        <div class="recent-post-no-background-image"></div>
                    </a>
                </div>
            @endif
            <div class="recent-post-details">
                <div class="recent-post-title">
                    <a class="recent-post-title-link" href="/{{ $post->type->slug }}/{{ $post->slug }}">
                        {{ mb_strimwidth(strip_tags($post->title), 0, 65, '...')  }}
                    </a>
                </div>
                <div class="recent-post-date">{{ $post->created_at->format('Y-m-d') }}</div>
            </div>
        </div>
    @else
        <div class="recent-post">
            <div class="recent-post-details">
                <div class="recent-post-title">
                    <a class="recent-post-title-link" href="/{{ $post->type->slug }}/{{ $post->slug }}">{{ $post->title }}</a>
                </div>
            </div>
        </div>
    @endif
@endforeach
</div>
