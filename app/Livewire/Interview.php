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
    public string $challenge_list_order = 'squares'; // list|squares
    public string $search = '';

    public function changeChallengeListOrderTo(string $list_order = 'list')
    {
        $this->challenge_list_order = $list_order;
    }

    public function updatedSelectedDifficulty()
    {
        $this->selected_challenges = null;
        $this->selected_topic_id = -1;
        $this->search = '';
    }

    public function updatedSelectedTopicId()
    {
        $this->search = '';
        $this->loadSelectedChallenges();
    }

    public function updatedSearch()
    {
        $this->loadSelectedChallenges();
    }

    protected function loadSelectedChallenges(): void
    {
        $this->selected_challenges = null;

        if (!$this->selected_topic_id || $this->selected_topic_id === -1) {
            return;
        }

        $this->selected_challenges = Challenge::byDifficultyAndTopic(
            selected_difficulty: $this->selected_difficulty,
            topic_id: $this->selected_topic_id,
            user_id: auth()->user()->id,
            return_cols: ['id', 'title', 'challenge_slug', 'banner_url', 'description'],
            search: $this->search,
        )->load(['languages', 'tags', 'topics']);
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
        session()->has('challenge_ids') ? session()->remove('challenge_ids') : '';
        $this->getDifficulties();
    }

    public function render()
    {
        $this->getTopics();
        return view('livewire.interview');
    }
}
