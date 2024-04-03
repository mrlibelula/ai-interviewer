<div class="flex flex-col gap-y-8">
    <x-heading class="-mb-12">
        @if ($challenge)
        <x-heading-content>
            <x-slot name="title">
                <div class="text-left">
                    {{ $challenge->title ?? 'n/a' }}
                </div>
            </x-slot>
            <x-slot name="subtitle">
                <div class="flex flex-col xl:flex-row items-start xl:items-center gap-x-4 gap-y-2 pt-4">
                    <x-pill class=" uppercase font-semibold">
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
                <div class="w-fit pb-2 flex items-center gap-x-2">
                    @foreach ($challenge->languages as $language)
                    <div class=" text-emerald-600 dark:text-emerald-500 text-base font-semibold">#{{ $language->name }}</div>
                    @endforeach
                </div>
            </x-slot>
            {{-- <x-slot name="right">
                <div class="flex items-center gap-x-3">
                    <a wire:navigate href="{{ route('interview') }}">
                        <x-secondary-button class=" whitespace-nowrap">Topics list</x-secondary-button>
                    </a>
                    @if ($challenge)
                    <x-button @click="$dispatch('getChallenge')" class=" whitespace-nowrap">
                        Next challenge
                    </x-button>
                    @endif
                </div>
            </x-slot> --}}
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
        <div class="flex w-full items-start gap-x-10">
            <!-- challenge -->
            <div class="w-[70%] pr-10 border-r_ border-dotted border-gray-800 dark:border-gray-500">
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
            <div class="w-[30%]">
                <div class="flex flex-col items-center gap-y-10 ">
                    
                    <div class=" w-full text-center text-gray-950 dark:text-gray-100">
                        @livewire('timer', ['time_limit' => $challenge->time_limit], key(uniqid()))
                    </div>

                    <div class="grid grid-cols-2 items-center gap-1 justify-between w-full text-gray-950 dark:text-gray-400 bg-gray-200 dark:bg-gray-800 p-1 rounded-lg shadow">
                        <div class="flex items-center gap-x-3 text-base justify-between bg-white dark:bg-gray-900 px-3 py-1.5 rounded-md w-full">
                            <div>Challenge XP</div>
                            <div class=" text-emerald-600 dark:text-emerald-400 font-semibold">
                                +{{ $challenge->difficulty->base_xp }}
                            </div>
                        </div>
                        <div class="flex items-center gap-x-3 text-base justify-between bg-white dark:bg-gray-900 px-3 py-1.5 rounded-md w-full">
                            <div>Bonus XP</div>
                            <div class=" text-emerald-600 dark:text-emerald-400 font-semibold">
                                +{{ '30' }}
                            </div>
                        </div>
                        <div class="flex items-center gap-x-3 text-base justify-between bg-white dark:bg-gray-900 px-3 py-1.5 rounded-md w-full">
                            <div>Solved</div>
                            <div class=" text-emerald-600 dark:text-emerald-400 font-semibold">
                                {{ '6/45' }}
                            </div>
                        </div>
                        <div class="flex items-center gap-x-3 text-base justify-between bg-white dark:bg-gray-900 px-3 py-1.5 rounded-md w-full">
                            <div>Tokens</div>
                            <div class=" text-emerald-600 dark:text-emerald-400 font-semibold">
                                {{ '130' }}
                            </div>
                        </div>
                        <div class="flex col-span-2 items-center gap-x-3 text-base justify-between bg-white dark:bg-gray-900 px-3 py-1.5 rounded-md w-full">
                            <div class=" text-gray-950 dark:text-gray-300 font-semibold">Total XP</div>
                            <div class=" text-emerald-600 dark:text-emerald-400 font-semibold">
                                {{ '130' }}
                            </div>
                        </div>
                        
                    </div>

                    @livewire('chatbot', ['chat_welcome' => $chat_welcome])
                    
                    
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
