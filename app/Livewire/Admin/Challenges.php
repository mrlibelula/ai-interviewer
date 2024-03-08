<?php

namespace App\Livewire\Admin;

use App\Tool;
use Livewire\Component;

class Challenges extends Component
{
    public string $current_route_name;
    public array|null $enviro = null;
    public $challenge = null;
    public bool $is_new = true;
    public $completion_text = null;
    public array $requirements = [
        'llm_prompt' => false, 
        'selected_topic' => false, 
        'selected_difficulty' => false, 
        'wildcards' => false, 
    ];

    public function requestChallenge()
    {
        $prompt = $this->enviro['string'];
        $response = Tool::getLLMChallenge($prompt);
        $this->is_new = $response->is_new;
        $this->challenge = $response->challenge;
        $this->completion_text = $response->completion_text;

        // dd($this->challenge);
    }

    /**
     * Checks if all requirements are set to 'true'
     *
     * @return boolean
     */
    public function canRequestAI(): bool
    {
        foreach ($this->requirements as $boolean) {
            if (!$boolean) return false;
        }
        return true;
    }

    /**
     * Checks if the requirements are valid
     * and updates the requirements array
     *
     * @param integer $nb_prompt_chars
     * @param integer $nb_difficulty_topic_chars
     * @return void
     */
    public function checkRequirements(int $nb_prompt_chars = 15, int $nb_difficulty_topic_chars = 3): void
    {
        // validate 'llm_prompt'
        if (isset($this->enviro['string'])) {
            if ($this->enviro['string'] && strlen($this->enviro['string']) > $nb_prompt_chars) {
                $this->requirements['llm_prompt'] = true;
            }
        }

        // validate 'selected_topic'
        if (isset($this->enviro['selected_topic'])) {
            if (strlen($this->enviro['selected_topic']) > $nb_difficulty_topic_chars) {
                $this->requirements['selected_topic'] = true;
            }
        }

        // validate 'selected_difficulty'
        if (isset($this->enviro['selected_difficulty'])) {
            if (strlen($this->enviro['selected_difficulty']) > $nb_difficulty_topic_chars) {
                $this->requirements['selected_difficulty'] = true;
            }
        }

        // validate 'wildcards'
        if (isset($this->enviro['string'])) {
            if (!preg_match('/\s\?\?(.*?)\s/', $this->enviro['string'])) {
                $this->requirements['wildcards'] = true;
            }
        }
    }

    protected function setEnviro()
    {
        $this->enviro = Tool::enviro('prompt');
    }

    public function mount()
    {
        $this->current_route_name = request()->route()->getName();    // tackles livewire route name problem (livewire.update)
        $this->setEnviro();
        $this->checkRequirements();
    }

    public function render()
    {
        return view('livewire.admin.challenges')
            ->layout('layouts.app');
    }
}
