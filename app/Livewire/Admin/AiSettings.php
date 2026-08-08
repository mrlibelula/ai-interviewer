<?php

namespace App\Livewire\Admin;

use App\Models\Enviro;
use App\Tool;
use Livewire\Component;

class AiSettings extends Component
{
    public string $current_route_name = 'admin-ai-settings';

    public string $welcome = '';
    public string $recommendations = '';
    public string $challenge_system = '';
    public string $analyze_user_code = '';
    public string $complexity_analysis = '';
    public string $feedback = '';
    public string $dalle = '';
    public string $challenge_generation = '';

    public function mount(): void
    {
        $this->loadTemplates();
    }

    public function loadTemplates(): void
    {
        $defaults = Tool::defaultPromptTemplates();
        $enviro = Enviro::first();
        $stored = is_array($enviro?->prompt_templates) ? $enviro->prompt_templates : [];

        foreach (Tool::PROMPT_TEMPLATE_KEYS as $key) {
            $this->{$key} = $stored[$key] ?? $defaults[$key] ?? '';
        }
    }

    public function save(): void
    {
        $enviro = Enviro::first();
        if (!$enviro) {
            $enviro = Enviro::create([
                'prompt' => ['parts' => []],
                'openai' => [
                    'usage' => new \stdClass(),
                    'request_limit' => new \stdClass(),
                    'token_limit' => new \stdClass(),
                ],
            ]);
        }

        $templates = [];
        foreach (Tool::PROMPT_TEMPLATE_KEYS as $key) {
            $templates[$key] = $this->{$key};
        }

        $enviro->prompt_templates = $templates;
        $enviro->save();

        Tool::toastr($this, [
            'message' => 'AI prompt templates saved',
        ], 'success');
    }

    public function resetToDefaults(): void
    {
        $defaults = Tool::defaultPromptTemplates();
        foreach (Tool::PROMPT_TEMPLATE_KEYS as $key) {
            $this->{$key} = $defaults[$key] ?? '';
        }

        Tool::toastr($this, [
            'message' => 'Loaded defaults (not saved yet — click Save)',
        ], 'success');
    }

    public function render()
    {
        return view('livewire.admin.ai-settings');
    }
}
