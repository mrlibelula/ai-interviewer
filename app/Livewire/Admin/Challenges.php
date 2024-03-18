<?php

namespace App\Livewire\Admin;

use App\Tool;
use Livewire\Component;

class Challenges extends Component
{
    public string $current_route_name;
    public array|null $enviro = null;
    public array $challenges = [];
    public $challenge = null;
    public array $requirements = [
        'llm_prompt' => false, 
        'selected_topic' => false, 
        'selected_language' => true, 
        'selected_difficulty' => false, 
        'wildcards' => false, 
    ];

    public function requestChallenge()
    {
        $this->challenge = null;
        $prompt = Tool::enviro('prompt')['string'];
        $llm_challenge = Tool::getLLMChallenge($prompt);
        $imported_challenge_obj = Tool::importAIChallenge($llm_challenge);
        $blueprint = Tool::enviro('prompt')['blueprint'];
        $selected_difficulty = Tool::enviro('prompt')['selected_difficulty'];
        $selected_topic = Tool::enviro('prompt')['selected_topic'];
        $selected_language = Tool::enviro('prompt')['selected_language'];
        $prompt = Tool::wildcards($blueprint, $selected_difficulty, $selected_topic, $selected_language);
        /*
            update enviro.prompt.string with 
            $prompt that contains recently imported " ??dbchallenges " 
            for next request, trying to avoid repeated challenges
        */
        Tool::updateEnviroPromptString($prompt);

        if ($imported_challenge_obj->challenge) {
            $this->challenge = $imported_challenge_obj->challenge;
            $this->challenges[] = $this->challenge->toArray();
        }

        $this->dispatch('spinner-off');
        
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
            if (Tool::enviro('prompt')['string'] && strlen(Tool::enviro('prompt')['string']) > $nb_prompt_chars) {
                $this->requirements['llm_prompt'] = true;
            }
        }

        // validate 'selected_topic'
        if (isset($this->enviro['selected_topic'])) {
            if (strlen(Tool::enviro('prompt')['selected_topic']) > $nb_difficulty_topic_chars) {
                $this->requirements['selected_topic'] = true;
            }
        }

        // validate 'selected_difficulty'
        if (isset($this->enviro['selected_difficulty'])) {
            if (strlen(Tool::enviro('prompt')['selected_difficulty']) > $nb_difficulty_topic_chars) {
                $this->requirements['selected_difficulty'] = true;
            }
        }

        // validate 'wildcards'
        if (isset($this->enviro['string'])) {
            if (!preg_match('/\s\?\?(.*?)\s/', Tool::enviro('prompt')['string'])) {
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
        return view('livewire.admin.challenges');
    }
}
