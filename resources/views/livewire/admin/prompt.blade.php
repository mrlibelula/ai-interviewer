<div>
    <x-admin.nav />
    
    <x-container>
        
        <p>Customizable prompts for the LLM, tailor prompts to specific coding concepts, languages, or difficulty levels. LLM output response will be a <x-bold>string</x-bold> with <x-bold>JSON</x-bold> data and <x-bold>solution code</x-bold>.</p>
        
        <x-h5>Prompt base text</x-h5>
        

        <!-- dot env -->
        <x-descr-list>
            <x-slot name="title">
                Full prompt from "<x-bold>.env</x-bold>" <x-spot>"OPENAI_PROMPT_BASE_TEXT"</x-spot> key
            </x-slot>
            <div class="flex flex-col lg:flex-row gap-y-4 gap-x-4 items-center lg:items-end">
                <textarea disabled class="form-textarea w-full h-[7rem] font-mono">{{ env('OPENAI_PROMPT_BASE_TEXT') ?? 'n/a' }}</textarea>
            </div>
        </x-descr-list>

        <x-h5>Build prompt</x-h5>

        <!-- Intro prompt text -->
        <x-descr-list>
            <x-slot name="title">
                Introduction prompt text
            </x-slot>
            <div>
                <textarea wire:model="build_01_intro_prompt_text" class="form-textarea w-full h-[5rem] font-mono"></textarea>
            </div>
        </x-descr-list>

        <!-- JSON -->
        <x-descr-list>
            <x-slot name="title">
                LLM output response will be in <x-bold>JSON</x-bold> format, with the following <x-spot>key/value</x-spot> pairs. The <x-spot>values</x-spot> represent an example of <x-bold>how the LLM should respond</x-bold>
            </x-slot>
            <div class=" grid grid-cols-3 gap-4">
                <div class="table-header">key</div>
                <div class="table-header">value</div>
                <div class="table-header">type</div>

                <!-- title -->
                <input value="title" class="table-row form-input" />
                <input value="" class="table-row form-input" />
                <input disabled value="string" class="table-row form-input" />
                
                <!-- challenge -->
                <input value="challenge" class="table-row form-input" />
                <input value="" class="table-row form-input" />
                <input disabled value="string" class="table-row form-input" />

                <!-- difficulty_level -->
                <input value="difficulty_level" class="table-row form-input" />
                <input value="easy|medium|hard" class="table-row form-input" />
                <input disabled value="string" class="table-row form-input" />

                <!-- time_limit -->
                <input value="time_limit" class="table-row form-input" />
                <input value="H:i:s" class="table-row form-input" />
                <input disabled value="string" class="table-row form-input" />
                
                <!-- hints -->
                <input value="hints" class="table-row form-input" />
                <input value="" class="table-row form-input" />
                <input disabled value="string" class="table-row form-input" />
                
                <!-- test_cases -->
                <input value="test_cases" class="table-row form-input" />
                <input value="{{ '"", ""' }}" class="table-row form-input" />
                <input disabled value="array" class="table-row form-input" />

                <!-- topics -->
                <input value="topics" class="table-row form-input" />
                <input value="{{ '"", ""' }}" class="table-row form-input" />
                <input disabled value="array" class="table-row form-input" />

                <!-- tags -->
                <input value="tags" class="table-row form-input" />
                <input value="{{ '"", ""' }}" class="table-row form-input" />
                <input disabled value="array" class="table-row form-input" />

                <!-- languages -->
                <input value="languages" class="table-row form-input" />
                <input value="{{ '"", ""' }}" class="table-row form-input" />
                <input disabled value="array" class="table-row form-input" />

                <!-- frameworks -->
                <input value="frameworks" class="table-row form-input" />
                <input value="{{ '"", ""' }}" class="table-row form-input" />
                <input disabled value="array" class="table-row form-input" />

                <!-- packages -->
                <input value="packages" class="table-row form-input" />
                <input value="{{ '"", ""' }}" class="table-row form-input" />
                <input disabled value="array" class="table-row form-input" />
                
            </div>
        </x-descr-list>

        <!-- JSON and solution_code separator -->
        <x-descr-list>
            <x-slot name="title">
                <x-spot>JSON</x-spot> and <x-spot>solution_code</x-spot> <x-bold>separator</x-bold>
            </x-slot>
            <div>
                <input wire:model="build_02_json_solution_code_separator" class="form-input w-full font-mono" />
            </div>
        </x-descr-list>

        <!-- Difficulty level -->
        <x-descr-list>
            <x-slot name="title">
                Difficulty level prompt
            </x-slot>
            <div>
                <textarea wire:model='build_03_difficulty_level' class="form-textarea w-full h-[5rem] font-mono"></textarea>
            </div>
        </x-descr-list>

        <!-- Solution code area -->
        <x-descr-list>
            <x-slot name="title">
                Solution code area prompt
            </x-slot>
            <div>
                <textarea wire:model='build_04_solution_code' class="form-textarea w-full h-[5rem] font-mono"></textarea>
            </div>
        </x-descr-list>

        <!-- How to treat arrays -->
        <x-descr-list>
            <x-slot name="title">
                How to treat arrays
            </x-slot>
            <div>
                <textarea wire:model='build_05_arrays' class="form-textarea w-full h-[5rem] font-mono"></textarea>
            </div>
        </x-descr-list>

        <!-- Append programming languages -->
        <x-descr-list>
            <x-slot name="title">
                Append programming languages
            </x-slot>
            <div>
                <textarea wire:model='build_06_languages' class="form-textarea w-full h-[5rem] font-mono"></textarea>
            </div>
        </x-descr-list>

        <!-- Random topic prompt -->
        <x-descr-list>
            <x-slot name="title">
                <x-bold>Random topic</x-bold> prompt
            </x-slot>
            <div>
                <textarea wire:model='build_07_random_topic' class="form-textarea w-full h-[5rem] font-mono"></textarea>
            </div>
        </x-descr-list>

        <!-- Selected topic prompt -->
        <x-descr-list>
            <x-slot name="title">
                <x-bold>Selected topic</x-bold> prompt
            </x-slot>
            <div>
                <textarea wire:model='build_07_selected_topic' class="form-textarea w-full h-[5rem] font-mono"></textarea>
            </div>
        </x-descr-list>

        <!-- Tags prompt -->
        <x-descr-list>
            <x-slot name="title">
                Tags prompt
            </x-slot>
            <div>
                <textarea wire:model='build_08_tags' class="form-textarea w-full h-[5rem] font-mono"></textarea>
            </div>
        </x-descr-list>

        <!-- End prompt -->
        <x-descr-list>
            <x-slot name="title">
                End prompt
            </x-slot>
            <div>
                <textarea wire:model='build_09_end_prompt' class="form-textarea w-full h-[5rem] font-mono"></textarea>
            </div>
        </x-descr-list>
        
    </x-container>
</div>
