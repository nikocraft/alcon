@php
    $settings = $renderData->block->getSettings();
    $posts = Content::where('content_type_id', $settings->postsType)->latest()->take($settings->numberOfPosts)->get();
@endphp

@foreach ($posts as $post)
    @if($settings->renderFeaturedImage)
        <div class="widget-post">
            @if($post->featuredimage)
            <div class="widget-post-thumbnail" style="flex: 0.5; margin-right: 12px;">
                <a href="/{{ $post->type->slug }}/{{ $post->slug }}">
                    <div style="background-image: url('{{ $post->featuredimage->medium }}'); background-position-y: center; background-size: cover; height: 70px;"></div>
                </a>
            </div>
            @else
            <div class="widget-post-thumbnail" style="flex: 0.5; margin-right: 12px;">
                    <a href="/{{ $post->type->slug }}/{{ $post->slug }}">
                        <div style="background-color: rgba(233, 239, 242, 0.6); height: 70px; justify-content: center; align-content: center;"><div></div></div>
                    </a>
                </div>
            @endif
            <div class="widget-post-details" style="flex: 1; display: flex; flex-direction: column;">
                <div class="widget-post-title">
                    <a class="widget-post-title-link" href="/{{ $post->type->slug }}/{{ $post->slug }}"><b>{{ mb_strimwidth(strip_tags($post->title), 0, 65, '...')  }}</b></a>
                </div>
                <div class="widget-post-date">{{ $post->created_at->format('Y-m-d') }}</div>
            </div>
        </div>
    @else 
        <div style="border-bottom: 1px dotted #d6d6d6; padding: 7px 0px;"><a style="color: #777;" href="/{{ $post->type->slug }}/{{ $post->slug }}">{{ $post->title }}</a></div>
    @endif
@endforeach
