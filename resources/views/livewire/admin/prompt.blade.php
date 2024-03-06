<div>
    <x-admin.nav currentRoute="{{ $currentRouteName }}" />
    
    <x-container>
        
        <p>Customizable prompts for the LLM, tailor prompts to specific coding concepts, languages, or difficulty levels. LLM output response will be a <x-bold>string</x-bold> with <x-bold>JSON</x-bold> data and <x-bold>solution code</x-bold>.</p>
        
        <x-h5>Prompt base text</x-h5>
        

        <!-- dot env blueprint -->
        <x-descr-list>
            <div class="flex flex-col gap-y-4 gap-x-4 items-start">
                <div>
                    Full blueprint prompt from "<x-bold>.env</x-bold>" <x-spot>"OPENAI_PROMPT_BASE_TEXT"</x-spot> key
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

        <x-h5>Build prompt</x-h5>

        <!-- select topic/s and difficulty -->
        <x-descr-list>
            <div class="flex justify-between gap-x-6">
                <!-- topics -->
                <div class="flex flex-col gap-y-4 gap-x-4 items-start w-full">
                    <div>
                        Select a Topic
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
                </div>
                <div class=" grid grid-cols-3 gap-4">
                    <div class="table-header">key</div>
                    <div class="table-header">value</div>
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

        <x-h5>Final generated prompt</x-h5>

        <x-descr-list>
            <div class="flex flex-col gap-y-4 gap-x-4 items-start">
                <div>
                    Concatenated <x-bold>prompt</x-bold> with filled wildcards <x-spot>" ??wildcard "</x-spot>
                </div>
                <textarea disabled wire:model="prompt" class="form-textarea w-full h-[20rem] font-mono text-sky-600 dark:text-sky-400"></textarea>
            </div>
        </x-descr-list>
        
    </x-container>
</div>
