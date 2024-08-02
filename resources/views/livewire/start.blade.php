<div class="flex flex-col gap-y-8">
    <x-heading class="-mb-8">
        @if ($challenge)
        <x-heading-content right_vertical_position="center">
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
                    <div class=" text-emerald-500 dark:text-emerald-400 text-base font-semibold">
                        {{ $language->name }}
                    </div>
                    @endforeach
                </div>
            </x-slot>
            <x-slot name="right">
                <div class="flex items-center gap-x-4">
                    @if ($is_challenge_solved)
                    {{-- <div class="group p-2_ rounded-md_ _bg-gradient-to-br _from-gray-500 _via-gray-400/70 to-gray-800/50_ shadow-md_ w-[2.3rem] h-[2.3rem]">
                        <x-icon-star class="w-full h-full text-amber-300 group-hover:animate-spin-y" fill="currentColor" />
                    </div> --}}
                    <div class="group p-2_ rounded-md_ _bg-gradient-to-br _from-gray-500 _via-gray-400/70 to-gray-800/50_ _shadow-md w-[2.5rem] h-[2.5rem] md:w-[3.5em] md:h-[3.5em]">
                        <x-icon-shield class=" w-full h-full group-hover:animate-spin-y" />
                    </div>
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
                            Start New Interview
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
            <div class="flex flex-col gap-y-12 xl:w-[70%] w-full xl:pr-[2.5rem] xl:border-r xl:border-dotted xl:border-gray-500 xl:dark:border-gray-600">
                <div class="flex flex-col gap-y-4">
                    
                    @livewire('challenge-card', [
                        'challenge' => $challenge, 
                        'header' => false,
                        'tags' => true,
                        'title' => false,
                        'footer' => false, 
                        'creators' => false, 
                    ], key(uniqid()))
                    
                    <x-h-accordion x-data="{ isOpen: true }" title="Try a solution">
                        <div class="mt-2">
                            <!-- code editor-->
                            <x-code-editor solverCode="{{ $challenge_attributes['solution_code'] ?? '' }}" />
                        </div>
                    </x-h-accordion>

                </div>

                <div>
                    &nbsp;
                </div>

            </div>

            <!-- right panel -->
            <div class="xl:w-[30%] w-full">
                <div class="flex flex-col items-center gap-y-10 ">

                    <x-countdown-timer time_limit="{{ $challenge->time_limit }}" class="text-[1.5rem] text-gray-950 dark:text-gray-300 tracking-widest font-mono text-center w-full" />

                    <!-- XP panel -->
                    <div class="grid grid-cols-2 items-center gap-1 justify-between w-full text-gray-950 dark:text-gray-400 bg-gray-200 dark:bg-gray-800 p-1 rounded-lg shadow">
                        <x-pill-xp label="Bonus XP">+{{ $total_user_bonus_xp }}</x-pill-xp>
                        <x-pill-xp label="Extra Bonus">+{{ $total_user_extra_xp }}</x-pill-xp>
                        <x-pill-xp label="Solved">
                            <div class="flex items-center gap-x-2 justify-between w-full">
                                @if ($is_challenge_solved)
                                <div class="group p-2_ rounded-md_ _bg-gradient-to-br _from-gray-500 _via-gray-400/70 to-gray-800/50_ shadow-md_ w-5 h-5">
                                    <x-icon-star class="w-full h-full text-amber-300 group-hover:animate-spin-y" fill="currentColor" />
                                </div>
                                @endif
                                {{ $solved_challenges_count . '/' . $total_challenges_count }}
                            </div>
                        </x-pill-xp>
                        <x-pill-xp label="Attempts">{{ $attempts }}</x-pill-xp>
                        <x-pill-xp class=" col-span-2" label="Total XP gained in this challenge">+{{ $total_bonus }}</x-pill-xp>
                        <x-pill-xp class=" col-span-2 dark:bg-gray-900/50" label="Overall Total XP">+{{ $total_user_bonus }}</x-pill-xp>
                    </div>

                    <!-- A.I. chatbot panel -->
                    @livewire('chatbot', [
                        'challenge' => $challenge,
                        'chat_welcome' => $chat_welcome,
                        'openai_chat_settings' => $openai_chat_settings,
                    ])

                    @if ($challenge)
                    <div class="flex items-center gap-x-3 justify-between mt-16">
                        @if ($is_challenge_solved && count($challenge_ids) > 1)
                        <x-secondary-button wire:click="nextChallenge">
                            Next challenge
                        </x-secondary-button>
                        @elseif ($is_challenge_solved)
                        <a wire:navigate href="{{ route('interview') }}">
                            <x-secondary-button>
                                Challenges List
                            </x-secondary-button>
                        </a>
                        @endif
                    </div>
                    @endif
                    
                </div>
            </div>
        </div>
        @else
        <x-not-found>It's over, see your results</x-not-found>
        @endif
    </x-container>

</div>
