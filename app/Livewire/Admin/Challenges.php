<?php

namespace App\Livewire\Admin;

use App\Tool;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Isolate;
use Livewire\Component;
use Throwable;

#[Isolate]
class Challenges extends Component
{
    public string $current_route_name;
    public array|null $enviro = null;
    /** @var array<int, int> Challenge IDs imported in the current batch (progress UI). */
    public array $batchChallengeIds = [];
    /** @var array<int, int> Challenge IDs imported during this page visit ("New" pills). */
    public array $sessionNewChallengeIds = [];
    public int $quantity = 1;
    public int $remaining = 0;
    public array $requirements = [
        'llm_prompt' => false, 
        'selected_topic' => false, 
        'selected_language' => true, 
        'selected_difficulty' => false, 
        'wildcards' => false, 
    ];

    /**
     * Starts a batch: one Livewire request per challenge (avoids nginx 504s).
     */
    public function startChallengeBatch(): void
    {
        try {
            $this->validate([
                'quantity' => 'required|integer|min:1|max:10',
            ]);
        } catch (ValidationException $e) {
            $this->dispatch('spinner-off');
            throw $e;
        }

        $this->remaining = (int) $this->quantity;
        $this->batchChallengeIds = [];
        $this->dispatch(
            'ai-challenges-updated',
            resetPage: true,
            newChallengeIds: $this->sessionNewChallengeIds,
        );
        $this->requestNextChallenge();
    }

    /**
     * Generates and imports a single challenge, then asks the browser for the next.
     */
    public function requestNextChallenge(): void
    {
        if ($this->remaining <= 0) {
            $this->dispatch('spinner-off');
            return;
        }

        try {
            $prompt = Tool::enviro('prompt')['string'];
            $llm_challenge = Tool::getLLMChallenge($prompt);

            if (!$llm_challenge || empty($llm_challenge->challenge)) {
                $this->failBatchRemaining(
                    'OpenAI timed out or failed to return a challenge. Already-imported challenges were kept.'
                );
                return;
            }

            $imported_challenge_obj = Tool::importAIChallenge($llm_challenge);
            $blueprint = Tool::enviro('prompt')['blueprint'];
            $selected_difficulty = Tool::enviro('prompt')['selected_difficulty'];
            $selected_topic = Tool::enviro('prompt')['selected_topic'];
            $selected_language = Tool::enviro('prompt')['selected_language'];
            /*
                Rebuild prompt with updated ??dbchallenges after each import
                so the next request in this batch avoids repeated titles.
            */
            $prompt = Tool::wildcards($blueprint, $selected_difficulty, $selected_topic, $selected_language);
            Tool::updateEnviroPromptString($prompt);

            if ($imported_challenge_obj->challenge) {
                $challengeId = (int) $imported_challenge_obj->challenge->id;
                $this->batchChallengeIds[] = $challengeId;
                $this->sessionNewChallengeIds[] = $challengeId;
            }

            $this->remaining--;
            $this->dispatch(
                'ai-challenges-updated',
                newChallengeIds: $this->sessionNewChallengeIds,
            );

            if ($this->remaining > 0) {
                /*
                    Chain the next import AFTER Livewire commits this response.
                    List refresh (incl. "New" pills) runs on the isolated child;
                    nested cards mount only when expanded, so remorph is safe.
                */
                $this->js('setTimeout(() => $wire.requestNextChallenge(), 50)');
            } else {
                $this->dispatch('spinner-off');
            }
        } catch (Throwable $e) {
            info(['requestNextChallenge' => $e->getMessage()]);
            $this->failBatchRemaining($e->getMessage());
        }
    }

    /**
     * Stops the batch cleanly: spinner off, toast, keep imports already done.
     */
    protected function failBatchRemaining(string $message): void
    {
        $imported = count($this->batchChallengeIds);
        $skipped = $this->remaining;
        $this->remaining = 0;
        $this->dispatch(
            'ai-challenges-updated',
            newChallengeIds: $this->sessionNewChallengeIds,
        );
        $this->dispatch('spinner-off');

        $summary = $imported > 0
            ? "Imported {$imported}, skipped {$skipped}. {$message}"
            : $message;

        Tool::toastr($this, [
            'title' => 'Challenge import interrupted',
            'message' => $summary,
        ], 'error');
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
