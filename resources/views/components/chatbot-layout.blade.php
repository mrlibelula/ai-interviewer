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
    {{ $attributes->merge(['class' => 'flex flex-col w-full']) }}
>
    <div class="h-[18rem] flex flex-col-reverse gap-y-6 bg-white dark:bg-slate-950/30 py-5 px-4 text-base overflow-hidden overflow-y-auto w-full leading-tight smooth-300 border border-gray-300 dark:border-slate-600/50 shadow">
        <div class="flex flex-col items-start gap-x-2 gap-y-4">
            {{ $slot }}
        </div>
        <div class="flex items-start gap-x-2 mt-1 font-semibold">
            <span class=" text-3xl">👋</span>
            Welcome to the Code Interview Challenge Chatbot!
        </div>
    </div>
    
    <div class="relative">
        <section x-cloak x-show="loader74" ><span class="loader-74 absolute -mt-1.5 z-40"> </span></section>
        <textarea 
            x-model="chat_message" 
            @keydown.enter.prevent="loader74 = true; error = false; $dispatch('sendMessage', { chatMessage: chat_message }); chat_message = '';" 
            id="chat-textarea" 
            class="absolute min-h-16 pr-16 bg-gray-200/40 dark:bg-slate-950/30 border-l border-r border-b border-t-0 border-gray-200 dark:border-slate-600/50 w-full rounded-b-lg overflow-hidden shadow focus:outline-none focus:ring dark:focus:border-slate-600/80 focus:bg-gray-50 dark:focus:bg-gray-950 focus:border-slate-600/80 dark:focus:ring-slate-800/80 focus:ring-gray-400 text-gray-950 dark:text-gray-300" 
            :class="{ ' bg-rose-400/70 placeholder-gray-900 dark:bg-rose-600/70 dark:placeholder-gray-300': error }"
            placeholder="Message to chatbot"
            required
        ></textarea>
        <div 
            @click.prevent="loader74 = true; error = false; $dispatch('sendMessage', { chatMessage: chat_message }); chat_message = '';" 
            class="absolute right-[1rem] top-[1rem] p-1 group cursor-pointer"
        >
            <x-icon-paper-plane class="w-6 h-6 text-gray-600 dark:text-slate-500 group-hover:text-black group-hover:dark:text-gray-400 smooth-300" stroke-width="2" />
        </div>
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
        <div class="mt-20 text-base text-rose-700 dark:text-rose-500" x-text="errorMessage">
            Error message
        </div>
    </template>

</div>