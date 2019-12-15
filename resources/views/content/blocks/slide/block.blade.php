@php
    $content = $renderData->block->content;
    $settings = $renderData->block->settings;
    // $slides = Slide::prepareSlides($renderData->allBlocks, $renderData->subBlocksIds);
    $subBlocksIds = $renderData->subBlocksIds;
    $allBlocks = $renderData->allBlocks;
    $currentBlock = $renderData->block;
    $display = ($renderData->key==0) ? '' : 'none !important';
@endphp

@includeIf('content.blocks.slide.css')

<li class="slide-block slide-{{ $renderData->block->unique_id }}" style="display: {{ $display }}">
    @if($renderData->sliderRenderStyle == 'boxed')
        <div class="container" style="height: 100%;">
            <div style="position: relative;">
                @php
                    $renderData = array();
                    $renderData['subBlocksIds'] = $subBlocksIds;
                    $renderData['allBlocks'] = $allBlocks;
                    $renderData['display'] = $settings->get('display');
                    $renderData['flexDirection'] = $settings->get('flexDirection');
                    $renderData = (object) $renderData;
                @endphp

                @component('content.render.blocks', [
                    'renderData' => $renderData,
                ])@endcomponent<!--
        --></div>
        </div>
    @else
        {{-- <div style="position: relative;"> --}}
            @php
                $renderData = array();
                $renderData['subBlocksIds'] = $subBlocksIds;
                $renderData['allBlocks'] = $allBlocks;
                $renderData['display'] = $settings->get('display');
                $renderData['flexDirection'] = $settings->get('flexDirection');
                $renderData = (object) $renderData;
            @endphp

            @component('content.render.blocks', [
                'renderData' => $renderData,
                'parentSettings' => $settings
            ])@endcomponent
    {{-- --></div> --}}
    @endif
</li>
