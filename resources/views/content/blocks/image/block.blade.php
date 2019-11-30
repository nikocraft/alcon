@php
    $block = $renderData->block;
    $content = $block->content;
    $settings = $block->settings;
    if(isset($content->filename)) {
        switch ($settings->imageSize) {
            case 'original':
                $imageUrl = '/' . $content->path .  $content->filename . '.' .  $content->extension;
            break;

            case 'large':
                $imageUrl = '/' .  $content->path .  $content->filename . '_large.' .  $content->extension;
            break;

            case 'medium':
                $imageUrl = '/' .  $content->path .  $content->filename . '_medium.' .  $content->extension;
            break;

            case 'thumb':
                $imageUrl = '/' .  $content->path .  $content->filename . '_thumb.' .  $content->extension;
            break;
        }
    }
@endphp

@includeIf('content.blocks.image.css')

@if(isset($content->filename))
    <div class="image-block image-{{ $block->unique_id }}">
        @if($settings->get('onClick') == 'lightbox')
            <span href="{{ '/' .  $content->path . $content->filename . '.' . $content->extension }}" class="image-lightbox">
                <img class="{{ $settings->get('customClass') }} @if($settings->get('imageResponsive'))img-responsive @endif" src="{{ $imageUrl }}" title="{{ $content->title }}" alt="{{ $content->title }}">
            </span>
        @elseif ($settings->get('onClick') == 'open-link')
            <a href="{{$content->get('link') }}" target="{{ $settings->get('target') }}">
                <img class="{{ $settings->get('customClass') }} @if($settings->get('imageResponsive'))img-responsive @endif" src="{{ $imageUrl }}" title="{{ $content->title }}" alt="{{ $content->title }}">
            </a>
        @else
            <img class="{{ $settings->get('customClass') }} @if($settings->get('imageResponsive'))img-responsive @endif" src="{{ $imageUrl }}" title="{{ $content->title }}" alt="{{ $content->title }}">
        @endif
    </div>
@endif
