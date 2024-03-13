<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Challenge as DBChallenge;
use App\Models\Difficulty;
use App\Models\Language;
use App\Models\Topic;
use App\Tool;

class Challenge extends Component
{
    public string $current_route_name;
    public $challenges;
    public $topics;
    public $languages;
    public $difficulties;
    public int $difficulty_id;
    
    public int $challenge_id;
    public $challenge;
    public $original_challenge; // a copy of the challenge for 'reset' purposes
    public bool $challenge_changed = false;

    public function updatedDifficultyId()
    {
        $this->challenge_changed = true;
        $this->challenge->difficulty_id = $this->difficulty_id;
        $this->challenge->save();
    }

    public function updatedChallengeId()
    {
        $this->challenge_changed = false;
        if ($this->challenge_id !== -1) {
            $this->loadChallenge();
            // for setting up difficulty
            $this->difficulty_id = $this->challenge->difficulty_id;
        } else {
            $this->challenge = null;
        }
    }

    public function getTopics()
    {
        $this->topics = Topic::select('id', 'name')->where('parent_id', '=', null)->orderBy('name', 'asc')->get();
    }

    public function getLanguages()
    {
        $this->languages = Language::select('id', 'name')->orderBy('name', 'asc')->get();
    }

    public function getDifficulties()
    {
        $this->difficulties = Difficulty::select('id', 'name')->get();
    }

    public function getChallenges()
    {
        $this->challenges = DBChallenge::with('topics:id,name')->select('id', 'title')->orderBy('title', 'asc')->get();
    }

    public function loadChallenge()
    {
        $this->challenge = Tool::fetchChallenge($this->challenge_id);
        $this->original_challenge = $this->challenge;   // for 'reset' purposes
    }

    public function toggleTopic(Topic $topic)
    {
        $this->challenge_changed = true;
        $this->challenge->topics->contains($topic)
            ? $this->challenge->removeTopic($topic)
            : $this->challenge->addTopic($topic);

        $this->loadChallenge();
    }

    public function mount()
    {
        $this->current_route_name = request()->route()->getName();    // tackles livewire route name problem (livewire.update)
        $this->getChallenges();
        $this->getTopics();
        $this->getLanguages();
        $this->getDifficulties();
    }

    public function render()
    {
        return view('livewire.admin.challenge')
            ->layout('layouts.app');
    }
}
