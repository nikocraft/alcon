@php
    $settings = $renderData->block->getSettings();
@endphp

@includeIf('content.blocks.columns.css')

<div class="columns-block columns-{{ $renderData->block->unique_id }} {{ $settings->customClass }}">
    @php
        $renderData->columnSpacing = $settings->columnSpacing
    @endphp

    @component('content.render.blocks', [
        'renderData' => $renderData,
    ])@endcomponent
</div>
