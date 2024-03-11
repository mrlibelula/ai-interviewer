<div class="flex flex-col gap-y-4">

    <template x-if="darkMode">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/github-dark.min.css" integrity="sha512-rO+olRTkcf304DQBxSWxln8JXCzTHlKnIdnMUwYvQa9/Jd4cQaNkItIUj6Z4nvW1dqK0SKXLbn9h4KwZTNtAyw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </template>
    
    <template x-if="!darkMode">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/default.min.css" integrity="sha512-hasIneQUHlh06VNBe7f6ZcHmeRTLIaQWFd43YriJ0UND19bvYRauxthDg8E4eVNPm9bRUhr5JGeqH7FRFXQu5g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </template>

    <script>hljs.highlightAll();</script>
    
    <!-- challenge bullets -->
    <div class="flex flex-col lg:flex-row gap-y-4 lg:gap-y-0 items-center gap-x-2">
        @isset($challenge->topics)    
            @if ($challenge->topics->count())
            <div class="px-3 py-0.5 text-sm text-center dark:bg-gray-700 rounded-full shadow-md">
                Topic/s: <x-bold>
                    @foreach ($challenge->topics as $topic)
                    {{ $topic->name }}@if (!$loop->last) {{ ', ' }} @endif
                    @endforeach
                </x-bold>
            </div>
            @endif
        @endisset
        <div class="px-3 py-0.5 text-sm text-center dark:bg-gray-700 rounded-full shadow-md">
            Difficulty: <x-bold>{{ ucfirst($challenge->difficulty->name) }}</x-bold>
        </div>
        <div class="px-3 py-0.5 text-sm text-center dark:bg-gray-700 rounded-full shadow-md">
            Status: <x-bold>{{ ucfirst($challenge->status->name) }}</x-bold>
        </div>
        <div class="px-3 py-0.5 text-sm text-center dark:bg-gray-700 rounded-full shadow-md">
            Visibility: <x-bold>{{ ucfirst($challenge->visibility->name) }}</x-bold>
        </div>
    </div>

    <div class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-300">
        {{ $challenge->title }}
    </div>

    @if (count($challenge->tags))
        <div class="-mt-1 flex items-center gap-x-3 text-base font-semibold text-sky-500 dark:text-sky-400">
        @foreach ($challenge->tags as $tag)
            <span>#{{ ucfirst(Str::camel($tag->name)) }}</span>
        @endforeach
        </div>
    @endif

    <x-h6 class="mt-6">Challenge description</x-h6>
    <div>
        {{ $challenge->description }}
    </div>

    
    @if (count(json_decode($challenge->test_cases, true)))
        <x-h6>Test cases</x-h6>
        <div class="flex flex-col">
            @foreach (json_decode($challenge->test_cases, true) as $case)
            <div>
                {{ $case }}
            </div>
            @endforeach
        </div>
    @endif

    @if ($challenge->hints)
    <x-h6>Hints</x-h6>

    <div>
        {{ $challenge->hints }}
    </div>
    @endif

    <x-h6>Time limit</x-h6>

    <div>
        {{ $challenge->time_limit }}
    </div>

    
    @if (count($challenge->languages))
        <x-h6>Languages</x-h6>
        <div class="flex items-center gap-x-3 text-base font-semibold text-sky-500 dark:text-sky-400">
        @foreach ($challenge->languages as $language)
            <span>#{{ ucfirst(Str::camel($language->name)) }}</span>
        @endforeach
        </div>
    @endif

    @if (count($challenge->frameworks))
    <x-h6>Frameworks</x-h6>
        <div class="flex items-center gap-x-3 text-base font-semibold text-sky-500 dark:text-sky-400">
        @foreach ($challenge->frameworks as $framework)
            <span>#{{ ucfirst(Str::camel($framework->name)) }}</span>
        @endforeach
        </div>
    @endif

    @if (count($challenge->packages))
    <x-h6>Packages/Libraries</x-h6>
        <div class="flex items-center gap-x-3 text-base font-semibold text-sky-500 dark:text-sky-400">
        @foreach ($challenge->packages as $package)
            <span>#{{ ucfirst(Str::camel($package->name)) }}</span>
        @endforeach
        </div>
    @endif


    <x-h6>Solution/Answer</x-h6>

    @livewire('code', [
        'language' => $challenge->languages->first()->name ?? 'plaintext', 
        'code' => $challenge->solution_code, 
    ], key(uniqid()))
    
    {{-- <div>
        {{ $challenge->chatgpt_prompt }}
    </div> --}}

    <x-h6>Footprint</x-h6>
    
    <div>
        <div class=" font-mono">
            {{ $challenge->completion_id }}
        </div>
        <div class=" font-mono">
            {{ $challenge->ai_model }}
        </div>
    </div>

    @if (count($challenge->creators))
    <x-h6>Creator/s</x-h6>
        <div class="flex items-center gap-x-3">
        @foreach ($challenge->creators as $creator)
            <span>{{ ucfirst(Str::camel($creator->name)) }}</span>
        @endforeach
        </div>
    @endif

</div>