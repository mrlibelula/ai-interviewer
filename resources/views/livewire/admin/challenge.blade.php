<div>

    <template x-if="darkMode">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/github-dark.min.css" integrity="sha512-rO+olRTkcf304DQBxSWxln8JXCzTHlKnIdnMUwYvQa9/Jd4cQaNkItIUj6Z4nvW1dqK0SKXLbn9h4KwZTNtAyw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </template>
    <template x-if="!darkMode">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/default.min.css" integrity="sha512-hasIneQUHlh06VNBe7f6ZcHmeRTLIaQWFd43YriJ0UND19bvYRauxthDg8E4eVNPm9bRUhr5JGeqH7FRFXQu5g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </template>

    <x-admin.nav currentRoute="{{ $current_route_name }}" />
    
    <x-container>
        @if (count($challenges) || filled($search) || $challenge)
            <div
                class="relative w-full"
                x-data="{ open: false }"
                @click.away="open = false"
                @keydown.escape.window="open = false"
                @challenge-picker-open="open = true"
            >
                <div class="relative">
                    <input
                        type="search"
                        wire:model.live.debounce.300ms="search"
                        @focus="open = true"
                        @click="open = true"
                        @keydown.arrow-down.prevent="open = true"
                        placeholder="Search and select a challenge…"
                        autocomplete="off"
                        class="form-input dark:bg-gray-800 text-lg md:text-xl w-full pr-20"
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center gap-x-1 pr-3 text-gray-400 dark:text-gray-500">
                        @if (filled($search))
                        <button
                            type="button"
                            wire:click="clearChallengeSearch"
                            @click="open = true"
                            class="p-1 rounded hover:text-gray-700 dark:hover:text-gray-200"
                            aria-label="Clear search"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                        @endif
                        <button
                            type="button"
                            @click="open = !open"
                            class="p-1 rounded hover:text-gray-700 dark:hover:text-gray-200"
                            aria-label="Toggle challenge list"
                        >
                            <svg
                                class="h-5 w-5 transition-transform duration-200"
                                :class="open ? 'rotate-180' : ''"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div
                    x-cloak
                    x-show="open"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    class="absolute z-50 mt-2 w-full overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg shadow-gray-900/20"
                >
                    <div class="flex items-center justify-between gap-x-3 gap-y-2 px-3 py-2 border-b border-gray-200/80 dark:border-gray-700/80 bg-gray-50/80 dark:bg-gray-900/40 flex-wrap">
                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            @if (filled($search))
                                Search results
                            @else
                                All challenges
                            @endif
                        </span>
                        <div class="flex items-center gap-2 flex-wrap">
                            <label class="sr-only" for="challenge-picker-sort">Sort challenges</label>
                            <select
                                id="challenge-picker-sort"
                                wire:model.live="sort"
                                @click.stop
                                @mousedown.stop
                                class="form-select text-xs h-8 py-1 pl-2 pr-7 dark:bg-gray-800 border-gray-200 dark:border-gray-700"
                            >
                                <option value="title_asc">Title A–Z</option>
                                <option value="title_desc">Title Z–A</option>
                                <option value="newest">Newest first</option>
                                <option value="oldest">Oldest first</option>
                            </select>
                            <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-semibold tracking-wide uppercase bg-gray-200/80 text-gray-700 dark:bg-gray-700/70 dark:text-gray-300">
                                {{ count($challenges) }} {{ count($challenges) === 1 ? 'match' : 'matches' }}
                            </span>
                        </div>
                    </div>

                    <div class="max-h-72 overflow-y-auto">
                        @forelse ($challenges as $db_challenge)
                            @php
                                $topicLabel = $db_challenge->topics
                                    ->pluck('name')
                                    ->filter()
                                    ->implode(', ');
                                $isSelected = (int) $challenge_id === (int) $db_challenge->id;
                            @endphp
                            <button
                                type="button"
                                wire:click="selectChallenge({{ $db_challenge->id }})"
                                @click="open = false"
                                wire:key="challenge-option-{{ $db_challenge->id }}"
                                class="flex w-full items-start sm:items-center justify-between gap-x-3 gap-y-1 px-3 py-3 text-left smooth-300 {{ $isSelected ? 'bg-sky-100/80 dark:bg-sky-500/15' : 'hover:bg-gray-100 dark:hover:bg-gray-700/70' }}"
                            >
                                <span class="min-w-0 flex-1 font-semibold text-base md:text-lg leading-snug break-words {{ $isSelected ? 'text-sky-900 dark:text-sky-200' : 'text-gray-900 dark:text-gray-100' }}">
                                    {{ $db_challenge->title }}
                                </span>
                                <span class="shrink-0 flex items-center gap-2">
                                    @if ($topicLabel !== '')
                                    <span class="max-w-[10rem] truncate inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-semibold tracking-wide uppercase bg-gray-200/80 text-gray-700 dark:bg-gray-700/70 dark:text-gray-300" title="{{ $topicLabel }}">
                                        {{ $topicLabel }}
                                    </span>
                                    @endif
                                    @if ($isSelected)
                                    <svg class="h-4 w-4 text-sky-600 dark:text-sky-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                    @endif
                                </span>
                            </button>
                        @empty
                            <div class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                No challenges match your search
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            @if ($challenge)
            <x-descr-list>
                <div class="flex flex-col gap-y-6 xl:gap-y-0 xl:flex-row items-start gap-x-6">
                    <!-- challege card -->
                    <div class=" w-full xl:w-3/4 p-6 bg-white dark:bg-gray-700/50 shadow-md rounded-lg">
                        @livewire('challenge-card', ['challenge' => $challenge], key(uniqid()))
                    </div>
                    <!-- setup challenge panel -->
                    <div class="w-full xl:w-1/4 flex flex-col gap-y-6 p-4">
                        
                        <x-h6 class="w-full text-center">Setup Challenge</x-h6>

                        <!-- time_limit setup -->
                        <x-admin.setup-box>
                            <x-slot name="title">Time limit</x-slot>
                            <div class="flex items-center gap-x-1">
                                <x-input wire:model.live='hours' type="number" min="0" max="23" class="form-input w-full" />:
                                <x-input wire:model.live='minutes' type="number" min="0" max="59" class="form-input w-full" />:
                                <x-input wire:model.live='seconds' type="number" min="0" max="59" class="form-input w-full" />
                            </div>
                        </x-admin.setup-box>
                        
                        <!-- topics setup -->
                        <x-admin.setup-box :fixed="true">
                            <x-slot name="title">Topics</x-slot>
                            <div>
                                @foreach ($topics as $list_language)
                                <x-admin.setup-box-list wire:click.prevent="toggleTopic({{ $list_language }})">
                                    <input type="checkbox" {{ $challenge->topics->contains($list_language) ? 'checked' : '' }} id="{{ $list_language->id }}" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-slate-600 shadow-sm focus:ring-slate-500 dark:focus:ring-slate-600 dark:focus:ring-offset-gray-800" />
                                    <label for="{{ $list_language->id }}" class="w-full whitespace-nowrap text-sm">
                                        {{ $list_language->name }}
                                    </label>
                                </x-admin.setup-box-list>
                                @endforeach
                            </div>
                            <x-slot name="selected">
                                <span>Selected: </span>
                                @foreach ($challenge->topics as $topic)
                                <span><x-bold>{{ $topic->name }}{{ !$loop->last ? ', ' : '' }}</x-bold></span>
                                @endforeach
                            </x-slot>
                        </x-admin.setup-box>

                        <!-- difficulty setup -->
                        <x-admin.setup-box>
                            <x-slot name="title">Difficulty</x-slot>
                            <div>
                                <select wire:model.live='difficulty_id' class="form-select text-lg md:text-xl w-full">
                                    @foreach ($difficulties as $difficulty)
                                    <option value="{{ $difficulty->id }}">
                                        {{ ucfirst($difficulty->name) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </x-admin.setup-box>

                        <!-- languages setup -->
                        <x-admin.setup-box :fixed="true">
                            <x-slot name="title">Languages</x-slot>
                            <div>
                                @foreach ($languages as $list_language)
                                <x-admin.setup-box-list wire:click.prevent="toggleLanguage({{ $list_language }})">
                                    <input type="checkbox" {{ $challenge->languages->contains($list_language) ? 'checked' : '' }} id="{{ $list_language->id }}" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-slate-600 shadow-sm focus:ring-slate-500 dark:focus:ring-slate-600 dark:focus:ring-offset-gray-800" />
                                    <label for="{{ $list_language->id }}" class="w-full whitespace-nowrap text-sm">
                                        {{ $list_language->name }}
                                    </label>
                                </x-admin.setup-box-list>
                                @endforeach
                            </div>
                            <x-slot name="selected">
                                <span>Selected: </span>
                                @foreach ($challenge->languages as $language)
                                <span><x-bold>{{ $language->name }}{{ !$loop->last ? ', ' : '' }}</x-bold></span>
                                @endforeach
                            </x-slot>
                        </x-admin.setup-box>

                        <!-- frameworks setup -->
                        <x-admin.setup-box :fixed="true">
                            <x-slot name="title">Frameworks</x-slot>
                            <div>
                                @foreach ($frameworks as $list_fw)
                                <x-admin.setup-box-list wire:click.prevent="toggleFramework({{ $list_fw }})">
                                    <input type="checkbox" {{ $challenge->frameworks->contains($list_fw) ? 'checked' : '' }} id="{{ $list_fw->id }}" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-slate-600 shadow-sm focus:ring-slate-500 dark:focus:ring-slate-600 dark:focus:ring-offset-gray-800" />
                                    <label for="{{ $list_fw->id }}" class="w-full whitespace-nowrap text-sm">
                                        {{ $list_fw->name }}
                                    </label>
                                </x-admin.setup-box-list>
                                @endforeach
                            </div>
                            <x-slot name="selected">
                                <span>Selected: </span>
                                @foreach ($challenge->frameworks as $fw)
                                <span><x-bold>{{ $fw->name }}{{ !$loop->last ? ', ' : '' }}</x-bold></span>
                                @endforeach
                            </x-slot>
                        </x-admin.setup-box>

                        <!-- packages setup -->
                        <x-admin.setup-box :fixed="true">
                            <x-slot name="title">Packages</x-slot>
                            <div>
                                @foreach ($packages as $list_package)
                                <x-admin.setup-box-list wire:click.prevent="togglePackage({{ $list_package }})">
                                    <input type="checkbox" {{ $challenge->packages->contains($list_package) ? 'checked' : '' }} id="{{ $list_package->id }}" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-slate-600 shadow-sm focus:ring-slate-500 dark:focus:ring-slate-600 dark:focus:ring-offset-gray-800" />
                                    <label for="{{ $list_package->id }}" class="w-full whitespace-nowrap text-sm">
                                        {{ $list_package->name }}
                                    </label>
                                </x-admin.setup-box-list>
                                @endforeach
                            </div>
                            <x-slot name="selected">
                                <span>Selected: </span>
                                @foreach ($challenge->packages as $package)
                                <span><x-bold>{{ $package->name }}{{ !$loop->last ? ', ' : '' }}</x-bold></span>
                                @endforeach
                            </x-slot>
                        </x-admin.setup-box>

                        <!-- tags setup -->
                        <x-admin.setup-box :fixed="true">
                            <x-slot name="title">Tags</x-slot>
                            <div>
                                @foreach ($tags as $list_tag)
                                <x-admin.setup-box-list wire:click.prevent="toggleTag({{ $list_tag }})">
                                    <input type="checkbox" {{ $challenge->tags->contains($list_tag) ? 'checked' : '' }} id="{{ $list_tag->id }}" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-slate-600 shadow-sm focus:ring-slate-500 dark:focus:ring-slate-600 dark:focus:ring-offset-gray-800" />
                                    <label for="{{ $list_tag->id }}" class="w-full whitespace-nowrap text-sm">
                                        {{ $list_tag->name }}
                                    </label>
                                </x-admin.setup-box-list>
                                @endforeach
                            </div>
                            <x-slot name="selected">
                                <span>Selected: </span>
                                @foreach ($challenge->tags as $tag)
                                <span><x-bold>{{ $tag->name }}{{ !$loop->last ? ', ' : '' }}</x-bold></span>
                                @endforeach
                            </x-slot>
                        </x-admin.setup-box>

                        <!-- status setup -->
                        <x-admin.setup-box>
                            <x-slot name="title">Status</x-slot>
                            <div>
                                <select wire:model.live='status_id' class="form-select text-lg md:text-xl w-full">
                                    @foreach ($statuses as $status)
                                    <option value="{{ $status->id }}">
                                        {{ ucfirst($status->name) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </x-admin.setup-box>

                        <!-- visibility setup -->
                        <x-admin.setup-box>
                            <x-slot name="title">Visibility</x-slot>
                            <div>
                                <select wire:model.live='visibility_id' class="form-select text-lg md:text-xl w-full">
                                    @foreach ($visibilities as $visibility)
                                    <option value="{{ $visibility->id }}">
                                        {{ ucfirst($visibility->name) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </x-admin.setup-box>

                        <div>
                            <x-danger-button @click="$dispatch('deleteChallenge')" class="w-full">
                                <div class="w-full flex items-center justify-center gap-x-4">
                                    <div>
                                        <x-icon-trash class="w-6 h-6" />
                                    </div>
                                    Move to Trash
                                </div>
                            </x-danger-button>
                        </div>
                        <div>
                            <!-- <x-danger-button :disabled="!$challenge_changed">Reset</x-danger-button> -->
                            {{-- <x-danger-button @click="$dispatch('destroyChallenge')" class="w-full">Delete challenge</x-danger-button> --}}
                        </div>
                    </div>
                </div>
            </x-descr-list>
            @endif

        @else
            <x-not-found>
                <div class="flex flex-col justify-center items-center gap-y-4 w-full">
                    <div class="py-4">
                        No challenges on database
                    </div>
                    <a href="/admin/challenges/">
                        <x-button>Request Challenge to A.I.</x-button>
                    </a>
                </div>
            </x-not-found>
        @endif

    </x-container>
</div>
