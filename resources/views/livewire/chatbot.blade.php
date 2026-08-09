<div x-data="{
    msg: null,
    listen: false,
    femaleChatVoice: false,

    toggleListen() {
        this.listen = !this.listen
        if (this.listen) this.listenChat()
        if (!this.listen) window.speechSynthesis.cancel()
    },
    
    listenChat() {
        if (this.listen) {
            this.msg = new SpeechSynthesisUtterance()
            this.msg.lang = 'en-US'
            this.msg.text = $wire.entangle('last_chatbot_message').initialValue
            this.msg.voice = this.femaleChatVoice 
                ? window.speechSynthesis.getVoices()[2] 
                : window.speechSynthesis.getVoices()[0]
            this.msg.volume = 1
            this.msg.rate = 1
            this.msg.pitch = 1
            window.speechSynthesis.speak(this.msg)
        }
    }, 
}" x-init="listenChat" @speak="listenChat" class="chatbot-panel flex flex-col items-center gap-y-4 w-full min-h-0 flex-1">
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
    <div class="chatbot-header bg-gray-300/40 dark:bg-slate-700/30 flex justify-between shadow-xl shadow-white dark:shadow-slate-950 z-30 items-center gap-x-4 w-full -mb-4 rounded-t-lg px-4 py-2 text-base shrink-0">
        <div class="flex items-center gap-x-2 text-emerald-600 font-semibold dark:text-slate-300">
            <x-circle color="emerald" class=" animate-pulse" />
            A.I. feedback chatbot 
        </div>
        <div class="flex items-center gap-x-4">
            <button @click="$dispatch('analyze-code'); $dispatch('chatbot-loader-on')" class="group cursor-pointer">
                <img class="w-[1.55rem] h-[1.55rem] grayscale group-hover:grayscale-0 smooth-300" src="https://cdn4.iconfinder.com/data/icons/artificial-intelligence-35/64/laptop-artificial-intelligence-ai-robot-512.png" alt="" title="Analyze my code/answer">
            </button>
            <div>
                <template x-if="!listen">
                    <x-icon-speaker-off @click="toggleListen" class=" w-6 h-6 text-gray-500 dark:text-gray-400 hover:text-black dark:hover:text-gray-200 smooth-300 cursor-pointer"><x-slot name="title">Turn on chat voice</x-slot></x-icon-speaker-off>
                </template>
                <template x-if="listen">
                    <x-icon-speaker @click="toggleListen" class=" w-6 h-6 text-gray-500 dark:text-gray-400 hover:text-black dark:hover:text-gray-200 smooth-300 cursor-pointer"><x-slot name="title">Turn off chat voice</x-slot></x-icon-speaker>
                </template>
            </div>
        </div>
    </div>
    
    <x-chatbot-layout
        :user-name="auth()->user()->name"
        :user-emoji="$user_emoji"
        :user-color="$user_color"
    >
        <!-- always needed (avoids first user message bug when hitting enter) -->
        <x-chatbot-message 
            divId="chat-{{ uniqid() }}" 
            avatar="{{ $chatbot_emoji }}"
            user="Assistant"
            color="{{ $chatbot_color }}"
            role="{{ 'assistant' }}"
            content="{{ $chat_welcome }}"
            :animate="count($messages) ? false : true"
        />
        @foreach ($messages as $message)
            @if (strtolower($message['role']) !== 'system')
            <x-chatbot-message 
                divId="chat-{{ uniqid() }}" 
                avatar="{{ $message['role'] === 'user' ? $user_emoji : $chatbot_emoji }}"
                user="{{ $message['role'] === 'user' ? auth()->user()->name : 'Assistant' }}"
                color="{{ $message['role'] === 'user' ? $user_color : $chatbot_color }}"
                role="{{ $message['role'] }}"
                content="{{ $message['content'] }}"
                :animate="$loop->last ? ($message['role'] === 'user' ? false : true) : false"
            />  
            @endif
        @endforeach
    </x-chatbot-layout>    
    
</div>
