<div class="flex flex-col gap-y-8">
    <x-heading>
        @if ($challenge)
        <x-heading-content>
            <x-slot name="title">
                <div class="text-left">
                    {{ $challenge->title ?? 'n/a' }}
                </div>
            </x-slot>
            <x-slot name="subtitle">
                <div class="flex flex-col xl:flex-row items-start xl:items-center gap-x-4 gap-y-2 pt-4">
                    <x-pill class="w-fit xl:w-[12%] text-gray-800 dark:text-gray-300 uppercase tracking-widest font-semibold bg-gradient-to-br from-gray-400 via-white to-gray-950/30 dark:from-gray-500 dark:via-black dark:to-gray-950/50 border-2 border-gray-300 dark:border-black">
                        {{ $challenge->difficulty->name }}
                    </x-pill>
                    <div class=" font-mono w-fit xl:w-[88%] text-left text-base">
                        @foreach ($challenge->topics as $topic)
                        {{ $topic->name }}{{ !$loop->last ? ', ' : '' }}
                        @endforeach
                    </div>
                </div>
            </x-slot>
            <x-slot name="top">
                <div class="w-fit pb-4 flex items-center gap-x-2">
                    @foreach ($challenge->languages as $language)
                    <x-pill>{{ $language->name }}</x-pill>
                    @endforeach
                </div>
            </x-slot>
            <x-slot name="right">
                <div class="flex items-center gap-x-3 ">
                    <a wire:navigate href="{{ route('interview') }}">
                        <x-secondary-button class=" whitespace-nowrap">Topics list</x-secondary-button>
                    </a>
                    @if ($challenge)
                    <x-button @click="$dispatch('getChallenge')" class=" whitespace-nowrap">Next challenge</x-button>
                    @endif
                </div>
            </x-slot>
        </x-heading-content>
        @else
        <x-heading-content title="Congratulations!!" subtitle="You've finished all the challenges in this topic">
            <x-slot name="right">
                <a wire:navigate href="{{ route('interview') }}">
                    <x-button>
                        <div class=" whitespace-nowrap">
                            Go back
                        </div>
                    </x-button>
                </a>
            </x-slot>
        </x-heading-content>
        @endif
    </x-heading>
    
    <x-container>
        @if ($challenge)
        <div class="flex w-full items-start gap-x-20">
            <!-- challenge -->
            <div class="w-[70%]">
                <div class="flex flex-col gap-y-8">
                    
                    @livewire('challenge-card', [
                        'challenge' => $challenge, 
                        'header' => false,
                        'title' => false,
                        'footer' => false, 
                        'creators' => false, 
                    ], key(uniqid()))
                    
                    <x-h5>Try a solution</x-h5>

                    <!-- code editor-->
                    <x-code-editor />
                </div>

            </div>

            <!-- right panel -->
            <div class="w-[30%] bg-gray-200/50 dark:bg-gray-800/70 rounded-lg py-4 px-4 shadow">
                <div class="flex flex-col items-center gap-y-4 ">
                    <x-descr-list class=" w-full text-center text-gray-100 bg-gray-500 dark:bg-gray-900">
                        <div class=" font-mono text-[1.8rem] text-center tracking-widest">
                            00<span class="dark:text-gray-300/60">:</span>30<span class="dark:text-gray-300/60">:</span>00
                        </div>
                    </x-descr-list>

                    <div class="flex items-center gap-x-2 justify-between w-full">
                        <x-pill2 label="Base XP" value="+{{ $challenge->difficulty->base_xp }} XP" />
                        <x-pill2 label="Total" value="1024 XP" />                        
                    </div>

                    {{-- <x-h6>Chatbot feedback</x-h6> --}}

                    <div class=" bg-gray-300/60 dark:bg-gray-800/80 flex justify-between items-center gap-x-4 w-full -mb-4 rounded-t-lg px-4 py-2 text-base">
                        <div class="flex items-center gap-x-2 dark:text-green-400">
                            <x-circle color="green" />
                            Feedback chatbot 
                        </div>
                        <div>
                            <x-icon-speaker-off class=" w-6 h-6 text-black dark:text-gray-300" />
                        </div>
                    </div>
                    <div class="flex flex-col-reverse gap-y-4 h-[20rem] bg-white dark:bg-black rounded-b-lg py-3 px-4 text-base shadow overflow-hidden overflow-y-auto w-full leading-tight smooth-300">
                        <div class="flex flex-col items-start gap-x-2">
                            
                            <span class="text-sky-700 dark:text-sky-400 font-semibold">🤖 Chatbot:</span>
                            <div id="chat" x-init="slowTextDisplay('{{ $chat_welcome }}', 50)">
                                <!-- chat messages -->
                            </div>

                        </div>
                        <div class=" font-semibold">
                            Welcome to our Code Interview Challenge Chatbot!
                        </div>
                    </div>

                    <textarea wire:model='chat_message' wire:keydown.enter="sendMessage" id="chat-textarea" class="form-textarea w-full" placeholder="Message ChatGPT"></textarea>
                    
                    <div>
                        options
                    </div>
                </div>
            </div>
        </div>
        @else
        <x-not-found>It's over, see your results</x-not-found>
        @endif
    </x-container>

    @if ($challenge)
    <x-container>
        <x-descr-list>
            <div class="flex items-center gap-x-3 justify-between">
                <div>
                    Previous challenge
                </div>
                <div>
                    Next challenge
                </div>
            </div>
        </x-descr-list>
    </x-container>
    @endif

    <script>
        function slowTextDisplay(text, delay = 100) {
            const parts = text.split(/(\s+)/);
            let index = 0;

            const intervalId = setInterval(function() {
                if (index < parts.length) {
                    var part = parts[index];
                    const chatElement = document.getElementById("chat");
                    part = decodeHTML(part);
                    //part.replace(/\\n/g, "\n")
                    console.log(part)
                    if (part === "\n") {
                        chatElement.appendChild(document.createElement("br"));
                    } else {
                        chatElement.appendChild(document.createTextNode(part));
                    }
                    index++;
                } else {
                    clearInterval(intervalId); // Clear the interval once all parts are displayed
                }
            }, delay);
        }

        function decodeHTML(html) {
            var txt = document.createElement("textarea");
            txt.innerHTML = html;
            return txt.value;
        }

        document.getElementById("chat-textarea").addEventListener("keydown", function(event) {
            if (event.key === "Enter") event.preventDefault()
        })

        /*
        window.addEventListener('DOMContentLoaded', (event) => {
            // Call slowTextDisplay function after DOM is loaded
            console.log('started!!!')
            slowTextDisplay("{{ $chat_welcome }}", 50);
        });
        */
    </script>
    
</div>
