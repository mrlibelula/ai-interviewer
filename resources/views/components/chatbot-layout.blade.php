<div x-data="{ chat_message: '' }" {{ $attributes->merge(['class' => 'flex flex-col']) }}>
    <div class="h-[18rem] flex flex-col-reverse gap-y-6 bg-white dark:bg-indigo-950/30 py-5 px-4 text-base overflow-hidden overflow-y-auto w-full leading-tight smooth-300 border border-gray-300 dark:border-indigo-800/70 shadow">
        <div class="flex flex-col items-start gap-x-2 gap-y-4">
            {{ $slot }}
        </div>
        <div class="flex items-start gap-x-2 mt-1 font-semibold">
            <span class=" text-3xl">👋</span>
            Welcome to the Code Interview Challenge Chatbot!
        </div>
    </div>

    <textarea 
        x-model="chat_message" 
        @keydown.enter="$dispatch('sendMessage', { chat_message })" 
        id="chat-textarea" 
        class="bg-white dark:bg-gray-950 border-l border-r border-b border-t-0 border-gray-200 dark:border-indigo-800/70 w-full rounded-b-lg overflow-hidden shadow focus:outline-none focus:ring dark:focus:border-indigo-800/80 focus:bg-gray-50 dark:focus:bg-gray-950 focus:border-indigo-800/80 dark:focus:ring-indigo-800/80 focus:ring-gray-400 text-gray-950 dark:text-gray-300" 
        placeholder="Message to A.I."
    ></textarea>

    <script>
        document.getElementById('chat-textarea').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') event.preventDefault()
        })
    </script>

</div>