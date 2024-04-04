<div class="flex flex-col gap-y-8">
    <x-heading class="-mb-8">
        @if ($challenge)
        <x-heading-content right_vertical_position="start">
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
                    <div class=" text-emerald-500 dark:text-emerald-500 text-base font-semibold">#{{ $language->name }}</div>
                    @endforeach
                </div>
            </x-slot>
            {{-- <x-slot name="right">
                <div class="flex items-center gap-x-4">
                    @if ($challenge)
                    <!-- <x-button @click="$dispatch('getChallenge')" class=" whitespace-nowrap"> -->
                    <x-button class=" whitespace-nowrap">
                        Finish
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
        <div class="flex flex-col gap-y-20 xl:gap-y-0 xl:flex-row w-full items-start gap-x-10">
            <!-- challenge -->
            <div class="flex flex-col gap-y-12 xl:w-[70%] w-full xl:pr-[2.5rem] border-r border-dotted border-gray-500 dark:border-gray-600">
                <div class="flex flex-col gap-y-4">
                    
                    @livewire('challenge-card', [
                        'challenge' => $challenge, 
                        'header' => false,
                        'title' => false,
                        'footer' => false, 
                        'creators' => false, 
                    ], key(uniqid()))
                    
                    <x-h-accordion x-data="{ isOpen: true }" title="Try a solution">
                        <div class="mt-2">
                            <!-- code editor-->
                            <x-code-editor />
                        </div>
                    </x-h-accordion>

                </div>

                @if ($challenge)
                <x-descr-list>
                    <div class="flex items-center gap-x-3 justify-between">
                        <div>
                            Select another Topic
                        </div>
                        <div>
                            Next challenge
                        </div>
                    </div>
                </x-descr-list>
                @endif

            </div>

            <!-- right panel -->
            <div class="xl:w-[30%] w-full">
                <div class="flex flex-col items-center gap-y-10 ">
                    
                    <!-- countdown timer -->
                    <x-countdown-timer time_limit="{{ $challenge->time_limit }}" class="text-[1.5rem] text-gray-950 dark:text-gray-300 tracking-widest font-mono text-center w-full" />
                    {{-- <x-countdown-timer time_limit="00:00:03" class="text-[1.5rem] text-gray-950 dark:text-gray-300 tracking-widest font-mono text-center w-full" /> --}}

                    <!-- XP panel -->
                    <div class="grid grid-cols-2 items-center gap-1 justify-between w-full text-gray-950 dark:text-gray-400 bg-gray-200 dark:bg-gray-800 p-1 rounded-lg shadow">
                        <x-pill-xp label="Challenge XP">+{{ $challenge->difficulty->base_xp }}</x-pill-xp>
                        <x-pill-xp label="Bonus XP">+{{ '30' }}</x-pill-xp>
                        <x-pill-xp label="Solved">{{ '6/45' }}</x-pill-xp>
                        <x-pill-xp label="Attempts">{{ '4' }}</x-pill-xp>
                        <x-pill-xp label="Total XP" class="col-span-2">{{ '130' }}</x-pill-xp>
                    </div>

                    <!-- A.I. chatbot panel -->
                    @livewire('chatbot', ['chat_welcome' => $chat_welcome])
                    
                </div>
            </div>
        </div>
        @else
        <x-not-found>It's over, see your results</x-not-found>
        @endif
    </x-container>

</div>
