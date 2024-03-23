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
                <div class="flex items-center gap-x-4 pt-4">
                    <x-pill class="text-gray-800 dark:text-gray-950 uppercase tracking-widest font-semibold bg-sky-300/70 dark:bg-sky-400/80">
                        {{ $challenge->difficulty->name }}
                    </x-pill>
                    <div class=" font-mono text-base">
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
                            'title' => false
                        ], key(uniqid()))
                    
                    <div>
                        editor
                    </div>
                    <div>
                        view solution
                    </div>
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

                    <x-h6>Chatbot feedback</x-h6>

                    <div class="flex flex-col-reverse gap-y-4 h-[20rem] bg-white dark:bg-black rounded-md py-1.5 px-2.5 text-base shadow overflow-hidden overflow-y-auto border-2 border-gray-300 dark:border-gray-700">
                        <div class="flex flex-col items-start gap-x-2">
                            <div class=" text-yellow-700 dark:text-yellow-400">
                                <span class="mr-2 dark:text-yellow-700">2024-03-21 12:45:21</span>Bot: 
                            </div>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Eius nesciunt qui quod voluptatem consectetur sit obcaecati ea ipsa minima illo nam dolor earum porro, aspernatur itaque, doloremque possimus, natus nemo illum autem? Provident praesentium eum, omnis vitae voluptatum voluptatibus atque.
                        </div>
                        <div class="flex flex-col items-start gap-x-2">
                            <div class=" text-yellow-700 dark:text-yellow-400">
                                <span class="mr-2 dark:text-yellow-700">2024-03-21 12:45:21</span>Libe: 
                            </div>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Ut nobis at sed, aspernatur inventore labore? Magnam natus eveniet labore distinctio?
                        </div>
                        <div class="flex flex-col items-start gap-x-2">
                            <div class=" text-yellow-700 dark:text-yellow-400">
                                <span class="mr-2 dark:text-yellow-700">2024-03-21 12:45:21</span>Bot: 
                            </div>
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Eveniet voluptate in a excepturi alias porro rerum officia suscipit nostrum sed quam voluptas quaerat culpa autem mollitia magnam, nesciunt ipsa pariatur.
                        </div>
                    </div>
                    

                    {{-- <x-pill2 class="w-full text-[1.15rem]">
                        <x-slot name="label">
                            <div class="py-2 bg-black w-full text-left">
                                Chatbot feedback
                            </div>
                        </x-slot>
                    </x-pill2> --}}
                    
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
    
</div>
