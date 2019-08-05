@php
    $content = $renderData->block->content;
    $settings = $renderData->block->getSettings();
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

            case 'grid_large':
                $imageUrl = '/' .  $content->path .  $content->filename . '_grid_large.' .  $content->extension;
            break;

            case 'grid_medium':
                $imageUrl = '/' .  $content->path .  $content->filename . '_grid_medium.' .  $content->extension;
            break;

            case 'thumb_large':
                $imageUrl = '/' .  $content->path .  $content->filename . '_thumb_large.' .  $content->extension;
            break;

            case 'thumb':
                $imageUrl = '/' .  $content->path .  $content->filename . '_thumb.' .  $content->extension;
            break;

            default:
                $imageUrl = '/' .  $content->path .  $content->filename . '_grid_medium.' .  $content->extension;
        }
    }
@endphp

@includeIf('content.blocks.image.css')

@if(isset($content->filename))
    <div class="image-block image-{{ $renderData->block->unique_id }}">
        @if($settings->onClick == 'lightbox')
            <a href="{{ '/' .  $content->path . $content->filename . '.' . $content->extension }}" class="image-lightbox">
                <img class="{{ $settings->customClass }} @if($settings->imageResponsive)img-responsive @endif" src="{{ $imageUrl }}" title="{{ $content->title }}" alt="{{ $content->title }}">
            </a>
        @elseif ($settings->onClick == 'open-link')
            <a href="{{$content->link }}" target="{{ $settings->target }}">
                <img class="{{ $settings->customClass }} @if($settings->imageResponsive)img-responsive @endif" src="{{ $imageUrl }}" title="{{ $content->title }}" alt="{{ $content->title }}">
            </a>
        @else
            <img class="{{ $settings->customClass }} @if($settings->imageResponsive)img-responsive @endif" src="{{ $imageUrl }}" title="{{ $content->title }}">
        @endif
    </div>
@endif
