@php
    $block = $renderData->block;
    $settings = $block->settings;
@endphp

@includeIf('content.blocks.tabs.css')

<div class="tabs-block tabs-{{ $block->unique_id }} {{ $settings->customClass }}">
    <div class="tabsbar">
        @foreach($renderData->subBlocksIds as $key => $tabId)
            @php
                $tab = $renderData->allBlocks[$tabId];
                $tabSettings = $tab->settings;
            @endphp
            <div id="tab-nav-{{ $tabSettings->get('blockRef') }}" class="tabsbar-item tab-item" onclick="Lara.openTab('tabs-{{ $block->unique_id }}', '{{ $tabSettings->get('blockRef') }}')">{{ $tabSettings->get('blockTitle') }}</div>
        @endforeach
    </div>
    @component('content.render.blocks', [
        'renderData' => $renderData,
    ])@endcomponent
</div>
