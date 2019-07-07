@php
    $settings = $renderData->block->getSettings();
    $parser = new \cebe\markdown\GithubMarkdown();
    $renderData->block->content = $parser->parse($renderData->block->content);
@endphp

    <div class="markdown-block {{ $settings->textClass }}" style="text-align: {{ $settings->textAlign }}">
        {!! $renderData->block->content !!}
    </div>
