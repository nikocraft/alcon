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

<div class="vimeo-block vimeo-block-{{ $block->unique_id }}">
    @if(isset($video))
            <iframe width="100%" height="100%" 
            src="https://player.vimeo.com/video/{{ $video }}?&autoplay={{ $settings->get('autoplay') }}" 
            style="position:absolute; top:0; left: 0"
            allow="{{ $allowFullscreen }}"
            {{ $allowFullscreenAttribute }}></iframe>
    @endif
</div>

@includeIf('content.blocks.vimeo.css')
