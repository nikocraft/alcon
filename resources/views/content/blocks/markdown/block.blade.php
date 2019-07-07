@php
    $settings = $renderData->block->getSettings();
    $parser = new \cebe\markdown\GithubMarkdown();
    $renderData->block->content = $parser->parse($renderData->block->content);
@endphp

<div class="text-block {{ $settings->textClass }} text-block-{{ $renderData->block->unique_id }}" style="text-align: {{ $settings->textAlign }}">
    {!! $renderData->block->content !!}
</div>
