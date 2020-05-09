@php
    $block = $renderData->block;
    $settings = $block->settings;
@endphp

@includeIf('content.blocks.code.css')

<div class="code-block code-{{$block->unique_id}} {{ $settings->get('customClass') }}">
    <pre class="line-numbers lang-{{ $settings->get('devLanguage') }}"><code class="line-numbers lang-{{ $settings->get('devLanguage') }}">{{ $block->content }}</code></pre>
</div>

