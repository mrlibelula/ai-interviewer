<div class="flex flex-col gap-y-10">
    <x-header-banner>
        <x-slot name="icon">
            <x-icon-rocket class="w-8 h-8 md:w-12 md:h-12 text-green-700 dark:text-green-400" />
        </x-slot>
        Choose your Learning Path
        <x-slot name="right">
            @if ($selected_topic_id && $selected_topic_id !== -1)
            <!-- not using wire:navigate because of chatbot animation bug, needs to reload entire page -->
            <a href="{{ route('interview-start', [
                \App\Tool::encode($selected_difficulty), 
                \App\Tool::encode($selected_topic_id), 
            ]) }}">
                <x-button class="flex justify-center">
                    <div class="text-xl px-8 w-full whitespace-nowrap">Start all</div>
                </x-button>
            </a>
            @else
            <x-secondary-button disabled class="flex justify-center">
                <div class="text-xl px-8 w-full whitespace-nowrap">Start all</div>
            </x-secondary-button>
            @endif
        </x-slot>
        
    </x-header-banner>

    <x-container>
        <div class="flex flex-col gap-y-16">
            <div class="flex flex-col gap-y-8">
                <p class=" text-center lg:text-left text-justify_ lg:text-2xl">
                    Receive <x-bold>real-time A.I. feedback</x-bold>, track time with a countdown timer, submit solutions promptly, and <x-bold>review results</x-bold> comprehensively for optimized technical interview preparation.
                </p>

                <x-descr-list>
                    <div class="flex p-4 flex-col lg:flex-row items-center lg:items-start justify-around gap-x-10 gap-y-8 lg:gap-y-0">
                        <div class="flex flex-col gap-y-4 text-center w-full lg:w-1/4">
                            <x-h5>Difficulty level</x-h5>
                            <select wire:model.live='selected_difficulty' class="form-select w-full md:text-2xl">
                                @foreach ($difficulties as $difficulty)
                                <option value="{{ strtolower($difficulty->name) }}">{{ ucfirst($difficulty->name) }} {{ ($nb = \App\Tool::challengesCountByDifficultyLevel($difficulty->name)) !== 0 ? '(' . $nb . ')' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-y-4 text-center w-full lg:w-3/4">
                            <x-h5>Interview Topic</x-h5>
                            <select wire:model.live='selected_topic_id' class="form-select w-full md:text-2xl">
                                <option value="-1"> -- Available "{{ strtolower($selected_difficulty) }}" topics -- </option>
                                @foreach ($topics as $topic)
                                    @if ($topic->challenges_count)
                                    <option value="{{ $topic->id }}">{{ $topic->name }} ({{ $topic->challenges_count }} challenge{{ $topic->challenges_count === 1 ? '' : 's' }})</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                    </div>

                </x-descr-list>
                
                @if ($selected_challenges)
                <x-h5>Available A.I. Challenges ({{ count($selected_challenges) }})</x-h5>
                <div class=" text-center">
                    <x-table class="bg-gray-300/30 dark:bg-gray-800/70 w-full">
                        @foreach ($selected_challenges as $challenge)
                        <tr class="group font-semibold hover:bg-gray-300/70 hover:dark:bg-gray-700 smooth-300">
                            <td class="py-6 px-1 w-16 text-base opacity-60">
                                {{ $loop->iteration }}
                            </td>
                            <td class="py-6 px-2 text-left">
                                <div class="flex items-center gap-x-6">
                                    <!-- challenge icon -->
                                    <div class="hidden lg:flex bg-gray-400 dark:bg-black w-[4.5rem] h-[4.5rem] rounded-md overflow-hidden border-2 border-gray-200 dark:border-gray-700 shadow">
                                        <img class=" w-full h-full" src="{{ $challenge->banner_url }}" alt="">
                                    </div>
                                    <div>
                                        @if ($challenge->languages->count())
                                        <div class=" text-base font-mono tracking-wide text-green-600 dark:text-green-400">
                                            @foreach ($challenge->languages as $language)
                                            {{ $language->name }}{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                        </div>
                                            @endif
                                        <div class=" text-2xl dark:text-gray-300 group-hover:text-gray-950 group-hover:dark:text-gray-100 smooth-300">
                                            {{ $challenge->title }}
                                        </div>
                                        <div class=" text-base text-gray-500 dark:text-gray-400 ">
                                        @if ($challenge->topics->count())
                                            @foreach ($challenge->topics as $topic)
                                            {{ $topic->name }}{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                        @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-6 px-2 w-36">
                                @if (\App\Tool::isChallengeSolved($challenge))
                                <div class="flex items-center gap-x-3 justify-end">
                                    <x-icon-star class="w-[2rem] h-[2rem] text-amber-500 dark:text-amber-300 animate-spin-y" fill="currentColor" />
                                    {{-- <x-icon-shield /> --}}
                                </div>
                                @endif
                            </td>
                            <td class="py-6 px-2 w-32 font-mono text-base text-left group-hover:text-gray-950 group-hover:dark:text-gray-100 smooth-300">
                                <!-- not using wire:navigate because of chatbot animation bug, needs to reload entire page -->
                                <a href="{{ route('interview-start', [
                                    \App\Tool::encode($selected_difficulty), 
                                    \App\Tool::encode($selected_topic_id), 
                                    \App\Tool::encode($challenge->id), 
                                    $challenge->challenge_slug, 
                                ]) }}">
                                    <x-secondary-button>
                                        <div class=" text-sm">
                                            Start
                                        </div>
                                    </x-secondary-button>
                                </a>
                            </td>                            
                        </tr>
                        @endforeach
                    </x-table>
                </div>
                @endif
            </div>
            
        </div>
    </x-container>
</div>
