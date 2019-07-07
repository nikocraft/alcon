@php
    $settings = $renderData->block->getSettings();
@endphp

@includeIf('content.blocks.tabs.css')

<div class="tabs-block tabs-{{ $renderData->block->unique_id }} {{ $settings->customClass }}">
    <div class="tabsbar">
        @foreach($renderData->subBlocksIds as $key => $tabId)
            @php
                $tab = $renderData->allBlocks[$tabId];
                $tabSettings = $tab->getSettings();
                $title = $tabSettings->blockTitle;
                $ref = $tabSettings->blockRef;
            @endphp
            <div id="tab-nav-{{ $ref }}" class="tabsbar-item tab-item" onclick="Lara.openTab('tabs-{{ $renderData->block->unique_id }}', '{{ $ref }}')">{{ $title }}</div>
        @endforeach
    </div>
    @component('content.render.blocks', [
        'renderData' => $renderData,
    ])@endcomponent
</div>
