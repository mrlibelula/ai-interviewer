@props([
    'userName' => 'You',
    'userEmoji' => '🐵',
    'userColor' => 'fuchsia',
])
@php
    $tw_colors = [
        'sky' => 'text-sky-700 dark:text-sky-400',
        'yellow' => 'text-yellow-700 dark:text-yellow-400',
        'emerald' => 'text-emerald-700 dark:text-emerald-400',
        'rose' => 'text-rose-700 dark:text-rose-400',
        'orange' => 'text-orange-700 dark:text-orange-400',
        'fuchsia' => 'text-fuchsia-700 dark:text-fuchsia-400',
        'violet' => 'text-violet-700 dark:text-violet-400',
        'purple' => 'text-purple-700 dark:text-violet-400',
        'pink' => 'text-pink-700 dark:text-violet-400',
        'green' => 'text-green-700 dark:text-green-400',
    ];
    $userTwColor = $tw_colors[$userColor] ?? $tw_colors['fuchsia'];
@endphp
<div x-data="{ 
        chat_message: '',
        loader74: false,
        error: false,
        errorMessage: 'no errors',
        pendingUserMessages: [],
        userName: {{ \Illuminate\Support\Js::from($userName) }},
        toggleError(event) {
            this.error = true
            const message = event.detail[0].error_message
            this.errorMessage = message
            this.pendingUserMessages = []
        },
        sendChat() {
            const message = this.chat_message
            if (!message.length) return
            this.pendingUserMessages.push(message)
            this.loader74 = true
            this.error = false
            this.$dispatch('sendMessage', { chatMessage: message })
            this.chat_message = ''
        }
    }" 
    @chatbot-loader-on.window="loader74 = true" 
    @chatbot-loader-off.window="loader74 = false; pendingUserMessages = []" 
    @chatbot-user-message-shown.window="pendingUserMessages = []" 
    @chatbot-error-true.window="toggleError($event)" 
    @chatbot-error-false.window="toggleError($event)" 
    {{ $attributes->merge(['class' => 'chatbot-layout flex flex-col w-full min-h-0']) }}
>
    <div class="chatbot-messages h-[18rem] flex flex-col-reverse gap-y-6 bg-white dark:bg-slate-950/30 py-5 px-4 text-base overflow-hidden overflow-y-auto w-full leading-tight smooth-300 border border-gray-300 dark:border-slate-600/50 shadow">
        <div class="flex flex-col items-start gap-x-2 gap-y-4">
            {{ $slot }}
            <template x-for="(msg, index) in pendingUserMessages" :key="'pending-user-' + index">
                <div class="flex flex-col gap-y-1" data-pending-user-message>
                    <span class="{{ $userTwColor }} font-semibold">{{ $userEmoji }} <span x-text="userName"></span>:</span>
                    <div class="tracking-wide leading-normal" x-text="msg"></div>
                </div>
            </template>
        </div>
        <div class="flex items-start gap-x-2 mt-1 font-semibold">
            <span class=" text-3xl">👋</span>
            Welcome to the Code Interview Challenge Chatbot!
        </div>
    </div>
    
    <div class="chatbot-input-wrap relative">
        <section
            x-cloak
            x-show="loader74"
            class="chatbot-loader absolute inset-x-0 top-0 z-40 pointer-events-none h-[4.8px] overflow-hidden"
            aria-hidden="true"
        >
            <span class="loader-74"></span>
        </section>
        <textarea
            x-model="chat_message"
            @keydown.enter.prevent="sendChat()"
            id="chat-textarea"
            rows="1"
            class="chatbot-textarea absolute min-h-16 pr-14 pl-3 bg-gray-200/40 dark:bg-slate-950/30 border-l border-r border-b border-t-0 border-gray-200 dark:border-slate-600/50 w-full rounded-b-lg overflow-hidden shadow focus:outline-none focus:ring dark:focus:border-slate-600/80 focus:bg-gray-50 dark:focus:bg-gray-950 focus:border-slate-600/80 dark:focus:ring-slate-800/80 focus:ring-gray-400 text-gray-950 dark:text-gray-300 leading-normal"
            :class="{ ' bg-rose-400/70 placeholder-gray-900 dark:bg-rose-600/70 dark:placeholder-gray-300': error }"
            placeholder="Message to chatbot"
            required
        ></textarea>
        <button
            type="button"
            @click.prevent="sendChat()"
            class="chatbot-send absolute right-3 top-1/2 -translate-y-1/2 p-1 group cursor-pointer inline-flex items-center justify-center"
            aria-label="Send message"
        >
            <x-icon-paper-plane class="w-5 h-5 text-gray-600 dark:text-slate-500 group-hover:text-black group-hover:dark:text-gray-400 smooth-300" stroke-width="2" />
        </button>
        <script>
            document.getElementById('chat-textarea').addEventListener('keydown', function(event) {
                if (event.key === 'Enter') event.preventDefault()
            })

            document.addEventListener('appended-chat-message', function(event) {
                document.getElementById('chat-textarea').value = ''
            })
        </script>
    </div>

    <template x-if="error">
        <div class="chatbot-error mt-20 text-base text-rose-700 dark:text-rose-500" x-text="errorMessage">
            Error message
        </div>
    </template>

</div>
