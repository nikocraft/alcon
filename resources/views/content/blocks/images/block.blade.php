@php
    $block = $renderData->block;
    $settings = $block->settings;
    $parentDisplay = isset($renderData->display) ? $renderData->display : 'block';
    $images = $block->content;
@endphp

@includeIf('content.blocks.images.css')

<div class="images-block images-block-{{ $block->unique_id }}">
    <div class="images-wrapper">
        @foreach ($images as $key => $image)
            @if(isset($image->filename))
                <div class="images-block-image">
                    @php
                        if($settings->get('imageSize') == 'original')
                            $imageUrl = '/' .  $image->path .  $image->filename . '.' .  $image->extension;
                        else
                            $imageUrl = '/' .  $image->path .  $image->filename .'_'. $settings->get('imageSize') .'.' .  $image->extension;
                    @endphp
                    @if($settings->get('onClick') == 'lightbox')
                        <a href="{{ '/' .  $image->path . $image->filename . '.' . $image->extension }}" class="image-lightbox">
                            <img class="{{ $settings->get('customClass')  }}"
                                src="{{ $imageUrl }}"
                                title="{{ $image->title }}"
                                alt="{{ $image->alt }}">
                        </a>
                    @else
                        <img class="{{ $settings->get('customClass') }}"
                            src="{{ $imageUrl }}"
                            title="{{ $image->title }}"
                            alt="{{ $image->alt }}">
                    @endif
                </div>
            @endif
        @endforeach
    </div>
</div>
