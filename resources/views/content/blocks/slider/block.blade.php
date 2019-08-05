@php
    $content = $renderData->block->content;
    $parentDisplay = isset($renderData->display) ? $renderData->display : 'block';
    $currentBlock = $renderData->block;
    $settings = $renderData->block->getSettings();
    $slides = App\Models\Core\Content\Block\SliderBlock::prepareSlides($renderData->allBlocks, $renderData->subBlocksIds);
    $renderData->sliderRenderStyle = $settings->contentRenderStyle;
@endphp

@includeIf('content.blocks.slider.css')

<component-wrapper component-name="slider" unique-id="{{$renderData->block->unique_id}}" :slides='{!! json_encode($slides, JSON_HEX_APOS | JSON_HEX_AMP) !!}' v-bind="{!! $renderData->block->getSettingsFilteredForFrontendRendering() !!}">
    <div class="slider-block slider-{{ $renderData->block->unique_id }}">
        @component('content.render.' . $renderData->blockRenderType, [
            'renderData' => $renderData,
            'folder' => 'slider'
        ])@endcomponent
    </div>
</component-wrapper>
