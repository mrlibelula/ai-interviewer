<div class="flex flex-col gap-y-4 overflow-hidden">

    <template x-if="darkMode">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/github-dark.min.css" integrity="sha512-rO+olRTkcf304DQBxSWxln8JXCzTHlKnIdnMUwYvQa9/Jd4cQaNkItIUj6Z4nvW1dqK0SKXLbn9h4KwZTNtAyw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </template>
    
    <template x-if="!darkMode">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/default.min.css" integrity="sha512-hasIneQUHlh06VNBe7f6ZcHmeRTLIaQWFd43YriJ0UND19bvYRauxthDg8E4eVNPm9bRUhr5JGeqH7FRFXQu5g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </template>

    <script>hljs.highlightAll();</script>
    
    <!-- challenge bullets -->
    <div class="flex items-center justify-between gap-x-4">
        <div class="flex flex-col lg:flex-row gap-y-4 lg:gap-y-0 items-center gap-x-2">
            @isset($challenge->topics)
                @if ($challenge->topics->count())
                <x-pill>
                    Topic/s: <x-bold>
                        @foreach ($challenge->topics as $topic)
                        {{ $topic->name }}@if (!$loop->last) {{ ', ' }} @endif
                        @endforeach
                    </x-bold>
                </x-pill>
                @endif
            @endisset
            <x-pill>Difficulty: <x-bold>{{ ucfirst($challenge->difficulty->name) }}</x-bold></x-pill>
            <x-pill>Status: <x-bold>{{ ucfirst($challenge->status->name) }}</x-bold></x-pill>
            <x-pill>Visibility: <x-bold>{{ ucfirst($challenge->visibility->name) }}</x-bold></x-pill>
        </div>
        <div class="flex flex-col lg:flex-row gap-y-4 lg:gap-y-0 items-center gap-x-2">
            @if ($challenge->languages->count())
            <x-pill-light><x-bold>
                @foreach ($challenge->languages as $lang)
                {{ ucfirst($lang->name) }}{{ !$loop->last ? ', ' : '' }}
                @endforeach
            </x-bold></x-pill-light>
            @endif
            <x-pill-light><x-bold>{{ $this->timeLimit($challenge->time_limit) }}</x-bold></x-pill-light>
        </div>
    </div>

    <div class="mt-2 text-3xl md:text-4xl font-semibold text-gray-900 dark:text-gray-300">
        {{ $challenge->title }}
    </div>

    @if (count($challenge->tags))
        <div class="-mt-1 gap-x-3 text-base text-wrap font-semibold text-sky-500 dark:text-sky-400">
        @foreach ($challenge->tags as $tag)
            <span>#{{ ucfirst(Str::camel($tag->name)) }}</span>
        @endforeach
        </div>
    @endif

    <div class="text-xl md:text-2xl">
        {{ $challenge->description }}
    </div>

    
    @if (count(json_decode($challenge->test_cases, true)))
        <x-h-accordion x-data="{ isOpen: true }">
            <x-slot name="title">
                Test cases
            </x-slot>
            <div class="flex flex-col">
                @foreach (json_decode($challenge->test_cases, true) as $case)
                <div>
                    {{ $case }}
                </div>
                @endforeach
            </div>
        </x-h-accordion>
    @endif

    @if ($challenge->hints)
    <x-h-accordion x-data="{ isOpen: true }">
        <x-slot name="title">
            Hints
        </x-slot>
        <div>{{ $challenge->hints }}</div>
    </x-h-accordion>

    @endif

    @if (count($challenge->frameworks))
    <x-h-accordion x-data="{ isOpen: true }">
        <x-slot name="title">
            Frameworks
        </x-slot>
        <div class="flex items-center gap-x-3 text-base font-semibold text-sky-500 dark:text-sky-400">
        @foreach ($challenge->frameworks as $framework)
            <span>#{{ ucfirst(Str::camel($framework->name)) }}</span>
        @endforeach
        </div>
    </x-h-accordion>
    @endif

    @if (count($challenge->packages))
    <x-h-accordion x-data="{ isOpen: true }">
        <x-slot name="title">
            Packages/Libraries
        </x-slot>
        <div class="flex items-center gap-x-3 text-base font-semibold text-sky-500 dark:text-sky-400">
        @foreach ($challenge->packages as $package)
            <span>#{{ ucfirst(Str::camel($package->name)) }}</span>
        @endforeach
        </div>
    </x-h-accordion>
    @endif


    <x-h-accordion x-data="{ isOpen: false }">
        <x-slot name="title">
            Solution/Answer
        </x-slot>
        <div>
            @livewire('code', [
                'language' => $challenge->languages->first()->name ?? 'plaintext', 
                'code' => $challenge->solution_code, 
            ], key(uniqid()))
        </div>
    </x-h-accordion>

    @if (count($challenge->creators))
    <x-h-accordion x-data="{ isOpen: true }">
        <x-slot name="title">
            Creator/s
        </x-slot>
        <div class="flex items-center gap-x-3">
        @foreach ($challenge->creators as $creator)
            <div class="flex items-center gap-x-3">
                <div class="w-7 h-7 overflow-hidden rounded-full shadow">
                    <img class="w-full h-full" src="{{ $creator->profile_photo_url }}">
                </div>
                <span>{{ ucfirst(Str::camel($creator->name)) }}</span>
            </div>
        @endforeach
        </div>
    </x-h-accordion>
    @endif

    <div class=" border-b border-gray-700 dark:border-gray-500 border-dotted py-2"></div>

    <div class="text-xs md:text-sm flex flex-col md:flex-row gap-y-1 md:gap-y-0 items-center justify-center md:justify-between text-gray-500 dark:text-gray-500 font-mono">
        <div>{{ $challenge->completion_id }}</div>
        <div>{{ $challenge->ai_model }}</div>
    </div>

</div>