<div>
    <x-admin.nav currentRoute="{{ $current_route_name }}" />

    <x-container class="gap-y-8">
        <div class="flex flex-col gap-y-2">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">AI admin settings</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Edit interview prompt templates stored in the database. Empty DB values fall back to
                <code class="text-xs">config/openai_prompts.php</code> / <code class="text-xs">.env</code>.
                Keep spaced wildcards like <code class="text-xs">" ??user_code "</code>.
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-500">
                Challenge generation and code analysis use API <code class="text-xs">json_schema</code> structured outputs
                (schemas live in PHP). Chatbot replies stay free text.
            </p>
        </div>

        <div class="rounded-lg border border-gray-300 dark:border-gray-600 p-4 text-sm text-gray-700 dark:text-gray-300">
            <div class="font-semibold mb-2">Available wildcards</div>
            <div class="font-mono text-xs leading-relaxed">
                ??difficulty_level, ??separator, ??languages, ??topics, ??tags, ??dbchallenges,
                ??challenge, ??topic, ??user, ??language, ??user_code, ??user_name,
                ??challenge_title, ??challenge_topic, ??solved_challenges_with_solver_code, ??feedback_type
            </div>
        </div>

        @php
            $fields = [
                'welcome' => 'Chatbot welcome message',
                'recommendations' => 'Per-turn interviewer instructions',
                'challenge_system' => 'Interview session system prompt',
                'analyze_user_code' => 'Run / A.I. analyze prompt',
                'complexity_analysis' => 'Time/space complexity prompt',
                'feedback' => 'Metrics aggregate feedback prompt',
                'dalle' => 'DALL·E challenge image prompt',
                'challenge_generation' => 'LLM challenge generation blueprint (content rules)',
            ];
        @endphp

        <div class="flex flex-col gap-y-6">
            @foreach ($fields as $key => $label)
                <div class="flex flex-col gap-y-2">
                    <label class="text-sm font-medium text-gray-800 dark:text-gray-200" for="tpl-{{ $key }}">{{ $label }}</label>
                    <textarea
                        id="tpl-{{ $key }}"
                        wire:model.defer="{{ $key }}"
                        rows="5"
                        class="form-textarea w-full font-mono text-sm bg-white dark:bg-gray-900 border-gray-300 dark:border-gray-600"
                    ></textarea>
                </div>
            @endforeach
        </div>

        <div class="flex flex-wrap gap-3">
            <x-secondary-button wire:click="save" wire:loading.attr="disabled">
                Save templates
            </x-secondary-button>
            <x-secondary-button wire:click="resetToDefaults" wire:loading.attr="disabled">
                Reset form to defaults
            </x-secondary-button>
        </div>
    </x-container>
</div>
