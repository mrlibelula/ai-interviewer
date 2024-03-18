<pre x-data="{ wrap: false }" x-init="hljs.highlightAll()" class="flex flex-col text-xl bg-gray-200/75 dark:bg-gray-700/60 px-1.5 rounded-lg overflow-hidden shadow-md cursor-text">
    <div class="flex items-center justify-between">
        <span class=" text-left px-1 py-1 text-gray-900 dark:text-gray-400">@if (strtolower($language) === 'html'){{ 'component' }}@elseif(strtolower($language) === 'plaintext'){{ 'code' }}@else{{ strtolower($language) }}@endif</span>
        <div @click.prevent="wrap = !wrap" class=" cursor-pointer mr-1">word wrap</div>
    </div>
    <code class="language-{{ $language ?? 'html' }} mb-2 rounded-lg max-h-96 overflow-auto  bg-white/75 dark:bg-black/40 p-4" :class="{ 'text-wrap': wrap }">{!! $code ?? '...' !!}</code>
</pre>