<?php

namespace App\Livewire;

use App\Models\Challenge;
use App\Models\Difficulty;
use App\Models\Topic;
use Livewire\Component;

class Interview extends Component
{
    public const SESSION_DIFFICULTY_KEY = 'interview.selected_difficulty';
    public const SESSION_TOPIC_KEY = 'interview.selected_topic_id';

    public $topics;
    public $difficulties;

    public string $selected_difficulty = 'easy';
    public int $selected_topic_id = -1;
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
        $this->persistSelection();
    }

    public function updatedSelectedTopicId()
    {
        $this->search = '';
        $this->loadSelectedChallenges();
        $this->persistSelection();
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

    protected function persistSelection(): void
    {
        session([
            self::SESSION_DIFFICULTY_KEY => $this->selected_difficulty,
            self::SESSION_TOPIC_KEY => $this->selected_topic_id,
        ]);
    }

    protected function restoreSelection(): void
    {
        $difficulty = session(self::SESSION_DIFFICULTY_KEY);
        $topicId = session(self::SESSION_TOPIC_KEY);

        if (is_string($difficulty) && $difficulty !== '') {
            $normalized = strtolower($difficulty);
            $exists = Difficulty::query()
                ->whereRaw('LOWER(name) = ?', [$normalized])
                ->exists();

            if ($exists) {
                $this->selected_difficulty = $normalized;
            }
        }

        $topicId = $topicId !== null && $topicId !== '' ? (int) $topicId : -1;
        $this->selected_topic_id = $topicId > 0 ? $topicId : -1;

        if ($this->selected_topic_id !== -1) {
            $this->loadSelectedChallenges();
        }
    }

    public function mount()
    {
        session()->has('challenge_ids') ? session()->remove('challenge_ids') : '';
        $this->getDifficulties();
        $this->restoreSelection();
    }

    public function render()
    {
        $this->getTopics();
        return view('livewire.interview');
    }
}
