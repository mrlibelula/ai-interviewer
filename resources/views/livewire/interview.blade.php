<div class="flex flex-col gap-y-10">
    <x-heading :hasBg="true">
        <div class="flex flex-col md:flex-row items-center gap-y-4 md:gap-x-8">
            <div class="flex justify-center md:justify-start items-center gap-x-4 w-full">
                <x-icon-academic-cap class="w-8 h-8 md:w-14 md:h-14 opacity-30 dark:opacity-70" />
                <h2 class="text-2xl md:text-3xl sm:text-4xl font-semibold text-gray-800 dark:text-gray-300 leading-tight">
                    Select your Learning Path
                </h2>
            </div>
            <div class="flex flex-col lg:flex-row items-center gap-x-8 gap-y-4 lg:gap-y-0 justify-center">
                <x-button class="w-full lg:w-fit flex justify-center">Begin</x-button>
            </div>
        </div>
    </x-heading>

    <x-container>
        <div class="flex flex-col gap-y-16">
            <div class="flex flex-col gap-y-16">
                <p class=" text-center lg:text-left text-justify_ lg:text-2xl">
                    Receive <x-bold>real-time A.I. feedback</x-bold>, track time with a countdown timer, submit solutions promptly, and <x-bold>review results</x-bold> comprehensively for optimized technical interview preparation.
                </p>

                <x-descr-list>
                    <div class="flex p-4 flex-col lg:flex-row items-center lg:items-start justify-around gap-x-10 gap-y-8 lg:gap-y-0">
                        <div class="flex flex-col gap-y-4 text-center w-1/4">
                            <x-h5>Select a difficulty level</x-h5>
                            <select wire:model.live='selected_difficulty' class="form-select w-full">
                                @foreach ($difficulties as $difficulty)
                                <option value="{{ strtolower($difficulty->name) }}">{{ ucfirst($difficulty->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-y-4 text-center w-3/4">
                            <x-h5>Select an interview Topic</x-h5>
                            <select wire:model.live='selected_topic_id' class="form-select w-full">
                                @foreach ($topics as $topic)
                                    @if ($topic->challenges_count)
                                    <option value="{{ $topic->id }}">{{ $topic->name }} ({{ $topic->challenges_count }} challenge{{ $topic->challenges_count === 1 ? '' : 's' }})</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </x-descr-list>
                
            </div>
            
        </div>
    </x-container>
</div>
