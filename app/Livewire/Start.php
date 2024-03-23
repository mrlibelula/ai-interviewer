<?php

namespace App\Livewire;

use App\Models\Challenge;
use App\Tool;
use Livewire\Component;

class Start extends Component
{
    public string $selected_difficulty;
    public int|null $selected_topic_id;
    public int|null $challenge_id;
    public $challenge = null;
    public string|null $challenge_slug;
    public array $challenge_ids = [];
    public bool $random = false;

    protected $listeners = ['getChallenge'];

    /**
     * Obtains the first available challange from the 'challenge_ids' list
     * and removes it from the list
     *
     * @return void
     */
    public function getChallenge()
    {
        $this->challenge = count($this->challenge_ids)
            ? Tool::fetchChallenge(array_shift($this->challenge_ids))
            : null;
    }

    public function getChallenges()
    {
        if ($challenge = $this->challenge_id ? Tool::fetchChallenge($this->challenge_id, ['id'], []) : null) {
            $this->challenge_ids[] = $challenge->id;
        } else {
            $challenges = Challenge::byDifficultyAndTopic($this->selected_difficulty, $this->selected_topic_id, ['id'], false);
            $challenges->each(function ($challenge) {
                $this->challenge_ids[] = $challenge->id;
            });
        }
        if ($this->random) shuffle($this->challenge_ids);
    }

    public function mount(string $enc_selected_difficulty, string $enc_selected_topic_id, string|null $enc_challenge_id = null, string|null $challenge_slug = null)
    {
        $this->selected_difficulty = Tool::decode($enc_selected_difficulty);
        $this->selected_topic_id = (int)Tool::decode($enc_selected_topic_id);
        $this->challenge_id = $enc_challenge_id ? Tool::decode($enc_challenge_id) : null;
        $this->challenge_slug = $challenge_slug;
        $this->getChallenges();
        $this->getChallenge();
    }

    public function render()
    {
        return view('livewire.start');
    }
}
