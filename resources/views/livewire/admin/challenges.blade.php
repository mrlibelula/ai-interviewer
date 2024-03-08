<div>
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
                Manually get LLM challenge/s
            </a>
            <x-dot class="breadcrumb-dot" />
            <a href="#jump-import" class="link">
                Import challenge/s
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

        <x-h5 id="jump-manually">Manually Get LLM Challenge/s</x-h5>

        <x-descr-list>
            <div class="flex items-center gap-x-4">
                @if ($this->canRequestAI())
                <div class="flex items-center gap-x-8">
                    <x-secondary-button 
                        wire:click='requestChallenge'
                        class=" bg-green-400 dark:bg-green-700"
                    >
                        Request challenge
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

            <!-- manual request challenge -->
            <div x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-75" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-75">
                
                @livewire('admin.manual-request-challenge')

            </div>

        </x-descr-list>

        <x-h5 id="jump-import">Import Challenge/s</x-h5>

        <x-descr-list>
            <div class="flex items-center gap-x-4">
                <x-secondary-button>Import all</x-secondary-button>
            </div>
        </x-descr-list>

    </x-container>
</div>
