<div 
    x-data="{ spinnerOn: false }"
    @spinner-off.window="spinnerOn = false"
    @spinner-on.window="spinnerOn = true"
>

    <template x-if="darkMode">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/github-dark.min.css" integrity="sha512-rO+olRTkcf304DQBxSWxln8JXCzTHlKnIdnMUwYvQa9/Jd4cQaNkItIUj6Z4nvW1dqK0SKXLbn9h4KwZTNtAyw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </template>
    <template x-if="!darkMode">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/default.min.css" integrity="sha512-hasIneQUHlh06VNBe7f6ZcHmeRTLIaQWFd43YriJ0UND19bvYRauxthDg8E4eVNPm9bRUhr5JGeqH7FRFXQu5g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </template>

    <x-admin.nav currentRoute="{{ $current_route_name }}" />

    <x-container>
        <x-breadcrumb>
            <a href="#jump-req" class="link">
                Requirements
            </a>
            <x-dot class="breadcrumb-dot" />
            <a href="#jump-test" class="link">
                OpenAI API connection test
            </a>
            <x-dot class="breadcrumb-dot" />
            <a href="#jump-auto" class="link">
                Automated LLM challenge/s completion
            </a>
            <x-dot class="breadcrumb-dot" />
            <a href="#jump-manually" class="link">
                Manually get LLM challenge/s completion
            </a>
            <x-dot class="breadcrumb-dot" />
            <a href="#jump-imported" class="link">
                Imported challenge/s
            </a>

        </x-breadcrumb>
    </x-container>

    <x-container>

        <x-h5 id="jump-req">Requirements</x-h5>

        <x-descr-list>
            <x-admin.required-container>

                <x-admin.required :checked="$requirements['llm_prompt']" id="check-prompt">
                    LLM prompt
                    <x-slot name="description">
                        @if ($requirements['llm_prompt'])
                        <div>Valid</div>
                        @else
                        <a class="link" wire:navigate href="/admin/prompt">Setup the LLM prompt</a>
                        @endif
                    </x-slot>
                </x-admin.required>

                <x-admin.required :checked="$requirements['selected_topic']" id="check-topic">
                    Selected topic
                    <x-slot name="description">
                        @if ($requirements['selected_topic'])
                        <div>{{ $enviro['selected_topic'] === strtolower('all topics') ? 'Random topic' : ucfirst($enviro['selected_topic']) }}</div>
                        @else
                        <a class="link" wire:navigate href="/admin/prompt">Setup the LLM prompt</a>
                        @endif
                    </x-slot>
                </x-admin.required>

                <x-admin.required :checked="$requirements['selected_difficulty']" id="check-difficulty">
                    Difficulty level
                    <x-slot name="description">
                        @if ($requirements['selected_difficulty'])
                        <div>{{ ucfirst($enviro['selected_difficulty']) ?? 'n/a' }}</div>
                        @else
                        <a class="link" wire:navigate href="/admin/prompt">Setup the LLM prompt</a>
                        @endif
                    </x-slot>
                </x-admin.required>

                <x-admin.required :checked="true" id="check-language">
                    Selected language
                    <x-slot name="description">
                        @if ($requirements['selected_language'])
                            @if(isset($enviro['selected_language']))
                            <div>{{ ucfirst($enviro['selected_language']) ?? 'n/a' }}</div>
                            @else
                            <div>{{ 'Any' }}</div>
                            @endif
                        @else
                        <a class="link" wire:navigate href="/admin/prompt">Setup the LLM prompt</a>
                        @endif
                    </x-slot>
                </x-admin.required>

                <x-admin.required :checked="$requirements['wildcards']" id="check-wildcards">
                    Wildcards
                    <x-slot name="description">
                        @if ($requirements['wildcards'])
                        <div>All set</div>
                        @else
                        <a class="link" wire:navigate href="/admin/prompt">Some are missing</a>
                        @endif
                    </x-slot>
                </x-admin.required>

            </x-admin.required-container>
        </x-descr-list>

        @if (!$this->canRequestAI())
        <div class="w-full text-center py-4 text-red-600 dark:text-red-400 -mt-4 text-base">
            There are some missing requirements in order to prompt a request to OpenAI
        </div>
        @endif

        <x-h5 id="jump-test">OPENAI API Connection test</x-h5>

        <x-descr-list>
            <div class="flex items-center gap-x-10">
                <x-secondary-button>Test OPENAI connection</x-secondary-button>
                <div>
                    Connection: <x-bold class=" text-green-600 dark:text-green-400">Established</x-bold>
                </div>
            </div>
        </x-descr-list>

        <x-h5 id="jump-auto">Automated LLM challenge/s completion</x-h5>

        <x-descr-list>
            <div class="flex items-center gap-x-4">
                <x-secondary-button>Setup</x-secondary-button>
                <x-secondary-button>Schedule</x-secondary-button>
            </div>
        </x-descr-list>

        <x-h5 id="jump-manually">Manually Get LLM Challenge/s completion</x-h5>

        <x-descr-list>
            <div class="flex items-center gap-x-4">
                @if ($this->canRequestAI())
                <div class="flex items-center gap-x-8">
                    <div class="flex items-center gap-x-3">
                        <label for="challenge-quantity" class="text-base whitespace-nowrap">Quantity</label>
                        <select
                            id="challenge-quantity"
                            wire:model.live="quantity"
                            class="form-select"
                            :disabled="spinnerOn"
                        >
                            @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- request button: one Livewire round-trip per challenge -->
                    <x-secondary-button 
                        wire:click="startChallengeBatch"
                        @click="if (!spinnerOn) $dispatch('spinner-on')"
                        x-bind:disabled="spinnerOn"
                        class=" bg-green-400 dark:bg-green-700"
                    >
                        <div class="relative flex items-start">
                            <div :class="{ 'text-transparent': spinnerOn }">
                                Request {{ $quantity === 1 ? 'challenge' : $quantity . ' challenges' }}
                            </div>
                            <div x-cloak x-show="spinnerOn" class=" absolute w-full flex justify-center">
                                <x-spinner class="w-6 h-6" />
                            </div>
                        </div>
                    </x-secondary-button>

                    <div class="flex items-center gap-x-3">
                        <div class=" text-base"><span class="link">This action may require some tokens</span> 🪙🪙🙉</div>
                        <x-icon-info class="w-6 h-6 text-gray-500" stroke-width="2" />
                    </div>
                </div>
                @else
                <a wire:navigate href="/admin/prompt/">
                    <x-danger-button>Not able to request challenge</x-danger-button>
                </a>
                @endif
            </div>

            <div x-cloak x-show="spinnerOn" class="mt-4 space-y-2">
                @if ($remaining > 0)
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Importing challenge {{ count($batchChallengeIds) + 1 }} of {{ count($batchChallengeIds) + $remaining }}…
                </div>
                @endif
                <div class="flex items-start gap-x-2 rounded-md border border-amber-300/70 dark:border-amber-600/50 bg-amber-50 dark:bg-amber-950/40 px-3 py-2 text-sm text-amber-800 dark:text-amber-200">
                    <x-icon-info class="w-5 h-5 shrink-0 mt-0.5 text-amber-600 dark:text-amber-400" stroke-width="2" />
                    <span>Stay on this page until imports finish. Leaving cancels any challenges still waiting to be requested.</span>
                </div>
            </div>

        </x-descr-list>

        <x-h5 id="jump-imported">Imported Challenge/s</x-h5>

        <livewire:admin.imported-challenges wire:key="imported-challenges-list" />

    </x-container>
</div>
