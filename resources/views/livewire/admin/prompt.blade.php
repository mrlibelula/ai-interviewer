<div>
    <x-admin.nav currentRoute="{{ $current_route_name }}" />
    
    <x-container>
        <x-breadcrumb>
            <a href="#jump-prompt-blueprint" class="link">
                Prompt blueprint
            </a>
            <x-dot class="breadcrumb-dot" />
            <a href="#jump-build-prompt" class="link">
                Build prompt
            </a>
            <x-dot class="breadcrumb-dot" />
            <a href="#jump-final-blueprint" class="link">
                Final generated blueprint
            </a>
            <x-dot class="breadcrumb-dot" />
            <a href="#jump-final-prompt" class="link">
                Final generated prompt
            </a>

        </x-breadcrumb>
    </x-container>

    <x-container>
        
        <p>This feature enables admin users to finely <x-bold>tune prompts</x-bold> according to specific coding concepts, languages, or difficulty levels. By leveraging this functionality, admin users can craft queries tailored to their exact needs, <x-bold>optimizing learning</x-bold> and <x-bold>problem-solving</x-bold> efficiency.</p>
        
        <x-h5 id="jump-prompt-blueprint">Prompt Blueprint (base text)</x-h5>
        

        <!-- dot env blueprint -->
        <x-descr-list>
            <div class="flex flex-col gap-y-4 gap-x-4 items-start">
                <div>
                    Full blueprint prompt
                </div>
                <textarea disabled class="form-textarea w-full h-[7rem] font-mono">{{ env('OPENAI_PROMPT_BASE_TEXT') ?? 'n/a' }}</textarea>
            </div>
            <div class="flex items-center gap-x-4">
                <div wire:click="loadBlueprintDataAndStoreToDB(true)" class="flex flex-col items-start">
                    <x-secondary-button class="mt-6">Rebuild prompt from this blueprint</x-secondary-button>
                </div>
                <x-secondary-button class="mt-6">Edit blueprint</x-secondary-button>
            </div>
            
        </x-descr-list>

        <x-h5 id="jump-build-prompt">Build prompt</x-h5>

        <!-- select topic/s and difficulty -->
        <x-descr-list>
            <div class="flex flex-col gap-y-6">
                <div class="flex flex-col xl:flex-row gap-y-6 xl:gap-y-0 justify-between xl:gap-x-6">
                    <!-- topics -->
                    <div class="flex flex-col gap-y-4 gap-x-4 items-start w-full">
                        <div class=" flex items-center gap-x-6">
                            <div class="flex items-center gap-x-2">
                                <input checked type="radio" class="form-radio" id="radio-select-topic" name="radio-topic">
                                <label for="radio-select-topic">
                                    Select a Topic
                                </label>
                            </div>
                            <div class="flex items-center gap-x-2">
                                <input type="radio" class="form-radio" id="radio-manual-topic" name="radio-topic">
                                <label for="radio-manual-topic">
                                    Input new Topic
                                </label>
                            </div>
                        </div>
                        
                        <select wire:model.live='selected_topic' class="form-select w-full">
                            @foreach ($topics as $topic)
                            <option value="{{ strtolower($topic) }}">{{ $topic }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- difficulty -->
                    <div class="flex flex-col gap-y-4 gap-x-4 items-start w-full">
                        <div>
                            Select a Difficulty
                        </div>
                        
                        <select wire:model.live='selected_difficulty' class="form-select w-full">
                            @foreach ($difficulties as $difficulty)
                            <option value="{{ strtolower($difficulty) }}">{{ ucfirst($difficulty) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- language -->
                    <div class="flex flex-col gap-y-4 gap-x-4 items-start w-full">
                        <div>
                            Select a Language
                        </div>
                        
                        <select wire:model.live='selected_language' class="form-select w-full">
                            <option value="any">Any language</option>
                            @foreach ($languages as $language)
                            <option value="{{ strtolower($language) }}">{{ ucfirst($language) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                {{-- <div>
                    Level of Creativity
                </div>
                <select _wire:model.live='selected_creativity' class="form-select w-full">
                    <option value="any">Any</option>
                </select> --}}
            </div>
            
        </x-descr-list>

        @foreach ($prompt_parts as $key => $part)
            @if (json_validate($part))
            <!-- JSON form table -->
            <x-descr-list>
                <div class="py-2 mb-8">
                    Part {{ $key + 1 }} - 
                    LLM output response will be in <x-bold>JSON</x-bold> format, with the following <x-spot>key/value</x-spot> pairs. The <x-spot>values</x-spot> represent an example of <x-bold>how the LLM should respond</x-bold>
                </div>
                <div class="pb-4 -mt-4">
                    <x-secondary-button>Add key</x-secondary-button>
                    <x-secondary-button>Remove key</x-secondary-button>
                    <x-secondary-button>Remove JSON</x-secondary-button>
                </div>
                <div class=" grid grid-cols-3 gap-4">
                    <div class="table-header">key</div>
                    <div class="table-header">value (valid JSON)</div>
                    <div class="table-header">type</div>

                    @php
                        $key_counter = -1;
                    @endphp
                    @foreach (json_decode($part, true) as $key => $value)
                    @php
                        $key_counter++;
                    @endphp
                    <input wire:model.live='json_keys.{{ $key_counter }}' class="table-row form-input" />
                    <input wire:model.live='json_values.{{ $key_counter }}' class="table-row form-input" />
                    <input disabled value="{{ gettype($value) }}" class="table-row form-input" />    
                    @endforeach

                </div>
                <div class="mt-8">
                    <x-secondary-button>Add key</x-secondary-button>
                    <x-secondary-button>Remove key</x-secondary-button>
                    <x-secondary-button>Remove JSON</x-secondary-button>
                </div>
            </x-descr-list>
            @else
            <x-descr-list>
                <div class="flex flex-col _lg:flex-row gap-y-4 gap-x-4 items-start">
                    <div>
                        Part {{ $key + 1 }}
                    </div>
                    <textarea wire:model.live="prompt_parts.{{ $key }}" class="form-textarea w-full h-[7rem] font-mono"></textarea>
                </div>
            </x-descr-list>
            @endif
        @endforeach

        <x-h5 id="jump-final-blueprint">Final generated blueprint</x-h5>

        <x-descr-list>
            <div class="flex flex-col gap-y-4 gap-x-4 items-start">
                <div>
                    Raw concatenated <x-spot>blueprint prompt</x-spot>
                </div>
                <textarea disabled wire:model="blueprint" class="form-textarea w-full h-[10rem] font-mono"></textarea>
            </div>
        </x-descr-list>

        <x-h5 id="jump-final-prompt">Final generated prompt</x-h5>

        <x-descr-list>
            <div class="flex flex-col gap-y-4 gap-x-4 items-start">
                <div>
                    Concatenated <x-bold>prompt</x-bold> with fulfilled wildcards <x-spot>" ??wildcard "</x-spot>
                </div>
                <textarea disabled wire:model="prompt" class="form-textarea w-full h-[20rem] font-mono text-sky-600 dark:text-sky-400"></textarea>
            </div>
        </x-descr-list>
        
    </x-container>
</div>
