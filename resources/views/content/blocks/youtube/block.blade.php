@php
    $block = $renderData->block;
    $settings = $block->settings;
    $content = $block->content;
    $parentDisplay = isset($renderData->display) ? $renderData->display : 'block';
    if(isset($content))
        $video = substr($content, strpos($content, "=") + 1);
@endphp

<div class="youtube-block youtube-block-{{ $block->unique_id }}">
    @if(isset($video))
        <div style="width: 100%; height: 100%;">
            <iframe width="100%" height="100%" src="https://www.youtube.com/embed/{{ $video }}" style="position:absolute; top:0; left: 0; border: 0;"></iframe>
        </div>
    @endif
</div>

@includeIf('content.blocks.youtube.css')
