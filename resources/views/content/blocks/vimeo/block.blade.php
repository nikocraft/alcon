@php
    $block = $renderData->block;
    $settings = $block->settings;
    $content = $block->content;
    $parentDisplay = isset($renderData->display) ? $renderData->display : 'block';
    if(isset($content))
        $video = substr($content, strpos($content, ".com/") + 5);

        $allowFullscreen = '';
        $allowFullscreenAttribute = '';
        $settings->get('allowFullscreen') ? $allowFullscreen = 'fullscreen;' : null;
        $settings->get('allowFullscreen') ? $allowFullscreenAttribute = 'allowfullscreen' : null;
@endphp

<div class="video-block-{{ $block->unique_id }}">
    @if(isset($video))
        <div style="width: 100%; height: 100%;">
            <iframe width="100%" height="100%" 
            src="https://player.vimeo.com/video/{{ $video }}?&autoplay={{ $settings->get('autoplay') }}" 
            style="border: 0;" 
            allow="{{ $allowFullscreen }}"
            {{ $allowFullscreenAttribute }}></iframe>
        </div>
    @endif
</div>

@includeIf('content.blocks.vimeo.css')
