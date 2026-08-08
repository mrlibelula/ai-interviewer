<div>
    @if ($code)
    @php
        $startsWrapped = substr_count($code, "\n") === 0;
    @endphp
    {{-- Outer must not be <pre>: browsers reparent nested <div>s out of <pre>, breaking Alpine `wrap` scope --}}
    <div
        x-data="{
            wrap: {{ $startsWrapped ? 'true' : 'false' }},
            highlight() {
                this.$nextTick(() => {
                    const el = this.$refs.code;
                    if (!window.hljs || !el) return;
                    delete el.dataset.highlighted;
                    el.classList.remove('hljs');
                    try { hljs.highlightElement(el); } catch (e) {}
                });
            }
        }"
        x-init="highlight()"
        @highlight-code.window="highlight()"
        class="flex flex-col text-xl bg-gray-300/75 dark:bg-gray-700/60 px-1.5 rounded-lg overflow-hidden shadow-md cursor-text"
    >
        <div class="flex items-center justify-between">
            <span class="text-left px-1 py-1 text-gray-900 dark:text-gray-400">
                @if (strtolower($language) === 'html')
                    {{ 'component' }}
                @elseif (strtolower($language) === 'plaintext')
                    {{ 'code' }}
                @else
                    {{ strtolower($language) }}
                @endif
            </span>
            <div @click.prevent="wrap = !wrap" class="cursor-pointer mr-1 select-none">word wrap</div>
        </div>
        <pre
            class="mb-2 min-h-[6rem] rounded-lg max-h-96 overflow-auto bg-white/75 dark:bg-black/40 p-2 text-base leading-relaxed"
            :class="wrap ? 'whitespace-pre-wrap break-words' : 'whitespace-pre'"
        ><code x-ref="code" class="language-{{ strtolower($language) ?: 'html' }}">{{ $code }}</code></pre>
    </div>
    @else
    <div class="flex justify-center font-mono bg-gray-300/75 dark:bg-gray-700/60 p-5 rounded-lg overflow-hidden shadow-md cursor-text">
        No solution code available
    </div>
    @endif
</div>
