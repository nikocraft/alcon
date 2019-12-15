@php
    $settings = $renderData->block->settings;
    $parser = new \cebe\markdown\GithubMarkdown();
    $renderData->block->content = $parser->parse($renderData->block->content);
@endphp

<div class="text-block {{ $settings->get('textClass') }} text-block-{{ $renderData->block->unique_id }}" style="text-align: {{ $settings->get('textAlign') }}">
    {!! $renderData->block->content !!}
</div>
