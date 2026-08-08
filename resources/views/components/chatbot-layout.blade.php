<div x-data="{ 
        chat_message: '',
        loader74: false,
        error: false,
        errorMessage: 'no errors',
        toggleError(event) {
            this.error = true
            const message = event.detail[0].error_message
            this.errorMessage = message
        }
    }" 
    @chatbot-loader-on.window="loader74 = true" 
    @chatbot-loader-off.window="loader74 = false" 
    @chatbot-error-true.window="toggleError($event)" 
    @chatbot-error-false.window="toggleError($event)" 
    {{ $attributes->merge(['class' => 'chatbot-layout flex flex-col w-full min-h-0']) }}
>
    <div class="chatbot-messages h-[18rem] flex flex-col-reverse gap-y-6 bg-white dark:bg-slate-950/30 py-5 px-4 text-base overflow-hidden overflow-y-auto w-full leading-tight smooth-300 border border-gray-300 dark:border-slate-600/50 shadow">
        <div class="flex flex-col items-start gap-x-2 gap-y-4">
            {{ $slot }}
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
            @keydown.enter.prevent="loader74 = true; error = false; $dispatch('sendMessage', { chatMessage: chat_message }); chat_message = '';"
            id="chat-textarea"
            rows="1"
            class="chatbot-textarea absolute min-h-16 pr-14 pl-3 bg-gray-200/40 dark:bg-slate-950/30 border-l border-r border-b border-t-0 border-gray-200 dark:border-slate-600/50 w-full rounded-b-lg overflow-hidden shadow focus:outline-none focus:ring dark:focus:border-slate-600/80 focus:bg-gray-50 dark:focus:bg-gray-950 focus:border-slate-600/80 dark:focus:ring-slate-800/80 focus:ring-gray-400 text-gray-950 dark:text-gray-300 leading-normal"
            :class="{ ' bg-rose-400/70 placeholder-gray-900 dark:bg-rose-600/70 dark:placeholder-gray-300': error }"
            placeholder="Message to chatbot"
            required
        ></textarea>
        <button
            type="button"
            @click.prevent="loader74 = true; error = false; $dispatch('sendMessage', { chatMessage: chat_message }); chat_message = '';"
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