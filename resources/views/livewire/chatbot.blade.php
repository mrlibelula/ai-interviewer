<div class="flex flex-col items-center gap-y-4">
    <!-- Feedback chatbot-->
    <div class=" bg-gray-300/40 dark:bg-indigo-800/40 flex justify-between shadow-xl shadow-white dark:shadow-indigo-950 z-30 items-center gap-x-4 w-full -mb-4 rounded-t-lg px-4 py-2 text-base">
        <div class="flex items-center gap-x-2 dark:text-indigo-400">
            <x-circle color="emerald" />
            Feedback A.I. chatbot 
        </div>
        <div>
            <x-icon-speaker-off class=" w-5 h-5 text-black dark:text-gray-400" stroke-width="2" />
        </div>
    </div>
    
    <x-chatbot-layout>
        <x-chatbot-message 
            divId="chat-01" 
            avatar="🤖"
            user="Chatbot"
            color="sky"
            text="{{ $chat_welcome }}" 
        />

        {{-- <x-chatbot-message 
            divId="chat-02" 
            avatar="💩"
            user="Timo"
            color="orange"
            text="Lorem ipsum dolor sit amet consectetur adipisicing elit.??Fugit, consequatur suscipit delectus voluptatibus nulla sed, fuga perspiciatis ipsa modi soluta ullam architecto, eos sit!.??Optio temporibus distinctio ipsa nostrum error." 
        /> --}}
    </x-chatbot-layout>    
    
</div>
