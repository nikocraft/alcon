
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
        @cssproperty([ 'property' => 'height', 'value' => $settings->get('featuredImageHeight') ]) @endcssproperty
        background-position-x: center;
        background-size: cover;
    }

    .recent-posts-block-{{ $block->unique_id }} .recent-post-details .recent-post-title .recent-post-title-link {
        @cssproperty([ 'property' => 'color', 'value' => $settings->get('titleColor') ]) @endcssproperty
        @cssproperty([ 'property' => 'font-size', 'value' => $settings->get('titleFontSize') ]) @endcssproperty
    }

    .recent-posts-block-{{ $block->unique_id }} .recent-post-details .recent-post-date {
        @cssproperty([ 'property' => 'color', 'value' => $settings->get('dateColor') ]) @endcssproperty
        @cssproperty([ 'property' => 'font-size', 'value' => $settings->get('dateFontSize') ]) @endcssproperty
    }

    .recent-posts-block-{{ $block->unique_id }} .recent-post-no-background-image {
        @cssproperty([ 'property' => 'height', 'value' => $settings->get('featuredImageHeight') ]) @endcssproperty
        background-color: rgba(255, 255, 255, 0.03);
        justify-content: center;
        align-content: center;
    }
@endpush