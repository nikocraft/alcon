@php
    $block = $renderData->block;
    $parentDisplay = isset($renderData->display) ? $renderData->display : 'block';
    
    $renderService = new App\Services\RenderContentService;
    $slides = $renderService->prepareSlides($renderData->allBlocks, $renderData->subBlocksIds);

    $renderData->sliderRenderStyle = $block->settings->get('contentRenderStyle');
    $settings = $block->settings;
@endphp

@includeIf('content.blocks.slider.css')

<component-wrapper component-name="slider" unique-id="{{$block->unique_id}}" :slides='{!! json_encode($slides, JSON_HEX_APOS | JSON_HEX_AMP) !!}' v-bind="{{ json_encode($settings->all()) }}">
    <div class="slider-block slider-{{ $block->unique_id }}">
        @component('content.render.' . $renderData->blockRenderType, [
            'renderData' => $renderData,
            'folder' => 'slider'
        ])@endcomponent
    </div>
</component-wrapper>
