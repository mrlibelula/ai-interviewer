<?php

namespace App\Livewire;

use App\Models\Difficulty;
use App\Models\Topic;
use App\Tool;
use Livewire\Component;

class Interview extends Component
{
    public $topics;
    public $difficulties;

    public string $selected_difficulty = 'easy';
    public int $selected_topic_id;

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
