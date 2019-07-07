@php
    $images = json_decode($renderData->block->content);
    $settings = $renderData->block->getSettings();
    $parentDisplay = isset($renderData->display) ? $renderData->display : 'block';
@endphp

@includeIf('content.blocks.images.css')

<div class="images-block images-{{ $renderData->block->unique_id }}">
    <div class="image-group">
        @foreach ($images as $key => $image)
            @if(isset($image->filename))
                <div class="image">
                    @php
                        if($settings->imageSize == 'original')
                            $imageUrl = '/' .  $image->path .  $image->filename . '.' .  $image->extension;
                        else
                            $imageUrl = '/' .  $image->path .  $image->filename .'_'. $settings->imageSize .'.' .  $image->extension;
                    @endphp
                    @if($settings->onClick == 'lightbox')
                        <a href="{{ '/' .  $image->path . $image->filename . '.' . $image->extension }}" class="image-lightbox" style="order: 2;">
                            <img class="{{ $settings->customClass }} @if($settings->imageResponsive) img-responsive @endif"
                                src="{{ $imageUrl }}"
                                title="{{ $image->title }}"
                                alt="{{ $image->alt }}">
                        </a>
                    @else
                        <img class="{{ $settings->customClass }} @if($settings->imageResponsive) img-responsive @endif"
                            src="{{ $imageUrl }}"
                            title="{{ $image->title }}"
                            alt="{{ $image->alt }}">
                    @endif
                </div>
            @endif
        @endforeach
    </div>
</div>
