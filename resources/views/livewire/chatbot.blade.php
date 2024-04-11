<div class="flex flex-col items-center gap-y-4">
    {{-- <div class="flex items-center gap-x-4 justify-between">
        <img title="Analyse my code" class=" w-1/6 grayscale hover:grayscale-0 hover:scale-125 smooth-300 cursor-pointer" src="https://ibuildings.com/img/blog/2019/07/img/Analyse_Your_Code_Static_Analysis_Gert_de_Pagter_0@500w.7f768d868c2b36494aa963f10913abc1.png" alt="">
        <img title="Analyse my code" class=" w-1/6 grayscale hover:grayscale-0 hover:scale-125 smooth-300 cursor-pointer" src="https://cdn-icons-png.freepik.com/512/5815/5815178.png" alt="">
        <img title="Analyse my code" class=" w-1/6 grayscale hover:grayscale-0 hover:scale-125 smooth-300 cursor-pointer" src="https://cdn-icons-png.flaticon.com/512/6404/6404558.png" alt="">
        
        <img title="Analyse my code" class=" w-1/6 grayscale hover:grayscale-0 hover:scale-125 smooth-300 cursor-pointer" src="https://cdn-icons-png.freepik.com/512/7069/7069916.png" alt="">
        <img title="Analyse my code" class=" w-1/6 grayscale hover:grayscale-0 hover:scale-125 smooth-300 cursor-pointer" src="https://cdn-icons-png.freepik.com/512/7069/7069896.png" alt="">
        <img title="Analyse my code" class=" w-1/6 grayscale hover:grayscale-0 hover:scale-125 smooth-300 cursor-pointer" src="https://cdn-icons-png.freepik.com/512/7069/7069888.png" alt="">
        <img title="Analyse my code" class=" w-1/6 grayscale hover:grayscale-0 hover:scale-125 smooth-300 cursor-pointer" src="https://cdn-icons-png.freepik.com/512/4191/4191138.png" alt="">
        <img title="Analyse my code" class=" w-1/6 grayscale hover:grayscale-0 hover:scale-125 smooth-300 cursor-pointer" src="https://cdn-icons-png.freepik.com/512/5371/5371136.png" alt="">
        <img title="Analyse my code" class=" w-1/6 grayscale hover:grayscale-0 hover:scale-125 smooth-300 cursor-pointer" src="https://cdn-icons-png.flaticon.com/512/4191/4191053.png" alt="">
        <img title="Analyse my code" class=" w-1/6 grayscale hover:grayscale-0 hover:scale-125 smooth-300 cursor-pointer" src="https://cdn-icons-png.flaticon.com/256/6404/6404298.png" alt="">

        <img title="Analyse my code" class=" w-1/6 grayscale hover:grayscale-0 hover:scale-125 smooth-300 cursor-pointer" src="https://cdn-icons-png.freepik.com/512/869/869283.png" alt="">
        <img title="Analyse my code" class=" w-1/6 grayscale hover:grayscale-0 hover:scale-125 smooth-300 cursor-pointer" src="https://cdn-icons-png.freepik.com/256/8264/8264488.png" alt="">
        
    </div> --}}

    <!-- Feedback chatbot-->
    <div class=" bg-gray-300/40 dark:bg-indigo-800/40 flex justify-between shadow-xl shadow-white dark:shadow-indigo-950 z-30 items-center gap-x-4 w-full -mb-4 rounded-t-lg px-4 py-2 text-base">
        <div class="flex items-center gap-x-2 dark:text-indigo-400">
            <x-circle color="emerald" />
            Feedback A.I. chatbot 
        </div>
        <div class="flex items-center gap-x-4">
            <div @click="$dispatch('analyze-code')" class="group cursor-pointer">
                <img class="w-7 h-7 grayscale group-hover:grayscale-0 smooth-300" src="https://cdn4.iconfinder.com/data/icons/artificial-intelligence-35/64/laptop-artificial-intelligence-ai-robot-512.png" alt="" title="Analyze my code/answer">
                {{-- <x-icon-dragonfly class="w-10 h-10 grayscale invert" /> --}}
                {{-- <x-icon-bug class="w-5 h-5 text-gray-400" /> --}}
            </div>
            <div>
                <x-icon-speaker-off class=" w-6 h-6 text-gray-500 dark:text-gray-400 hover:text-black dark:hover:text-gray-200 smooth-300 cursor-pointer"><x-slot name="title">Turn on chat voice</x-slot></x-icon-speaker-off>
            </div>
        </div>
    </div>
    
    <x-chatbot-layout>
        <!-- always needed (avoids first user message bug when hitting enter) -->
        <x-chatbot-message 
            divId="chat-{{ uniqid() }}" 
            avatar="{{ $chatbot_avatar }}"
            user="Chatbot"
            color="{{ $chatbot_color }}"
            role="{{ 'assistant' }}"
            content="{{ $chat_welcome }}"
            :animate="count($messages) ? false : true"
        />
        @foreach ($messages as $message)
            @if (strtolower($message['role']) !== 'system')
            <x-chatbot-message 
                divId="chat-{{ uniqid() }}" 
                avatar="{{ $message['role'] === 'user' ? $user_avatar : $chatbot_avatar }}"
                user="{{ $message['role'] === 'user' ? auth()->user()->name : 'Chatbot' }}"
                color="{{ $message['role'] === 'user' ? $user_color : $chatbot_color }}"
                role="{{ $message['role'] }}"
                content="{{ $message['content'] }}"
                :animate="$loop->last ? ($message['role'] === 'user' ? false : true) : false"
            />  
            @endif
        @endforeach
    </x-chatbot-layout>    
    
</div>
