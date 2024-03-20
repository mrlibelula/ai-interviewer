<?php

namespace App\Livewire;

use App\Models\Challenge;
use App\Models\Difficulty;
use App\Models\Topic;
use Livewire\Component;

class Interview extends Component
{
    public $topics;
    public $difficulties;

    public string $selected_difficulty = 'easy';
    public int $selected_topic_id;
    public $selected_challenges;

    public function updatedSelectedDifficulty()
    {
        $this->selected_challenges = null;
        $this->selected_topic_id = -1;
    }

    public function updatedSelectedTopicId()
    {
        $this->selected_challenges = null;
        if ($this->selected_topic_id && $this->selected_topic_id !== -1) {
            $this->selected_challenges = Challenge::byDifficultyAndTopic($this->selected_difficulty, $this->selected_topic_id);
        }
    }

    public function getTopics()
    {
        $this->topics = Topic::byDifficultyWithCountChallenges($this->selected_difficulty);
    }

    public function getDifficulties()
    {
        $this->difficulties = Difficulty::select('id', 'name')->get();
    }

    public function mount()
    {
        $this->getDifficulties();
    }

    public function render()
    {
        $this->getTopics();
        return view('livewire.interview');
    }
}
