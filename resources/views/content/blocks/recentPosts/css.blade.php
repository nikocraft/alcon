
@push('content-block-css')
    .recent-posts-block-{{ $block->unique_id }} .recent-post {
        display: flex;
        flex-direction: row;
        margin-bottom: 10px;
    }

    .recent-posts-block-{{ $block->unique_id }} .recent-post-featured-image {
        flex: 0.5;
        margin-right: 12px;
    }

    .recent-posts-block-{{ $block->unique_id }} .recent-post-details {
        flex: 1;
    }

    .recent-posts-block-{{ $block->unique_id }} .recent-post-featured-image .background-image {
        height: {{ $settings->get('featuredImageHeight') }};
        background-position-x: center;
        background-size: cover;
    }

    .recent-posts-block-{{ $block->unique_id }} .recent-post-details .recent-post-title .recent-post-title-link {
        color: {{ $settings->get('titleColor') }};
        font-size: {{ $settings->get('titleFontSize') }};
    }

    .recent-posts-block-{{ $block->unique_id }} .recent-post-details .recent-post-date {
        color: {{ $settings->get('dateColor') }};
        font-size: {{ $settings->get('dateFontSize') }};
    }

    .recent-posts-block-{{ $block->unique_id }} .recent-post-no-background-image {
        height: {{ $settings->get('featuredImageHeight') }};
        background-color: rgba(255, 255, 255, 0.03);
        justify-content: center;
        align-content: center;
    }
@endpush