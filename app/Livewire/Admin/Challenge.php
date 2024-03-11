<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Challenge as DBChallenge;

class Challenge extends Component
{
    public string $current_route_name;
    public $challenges;
    public int $challenge_id;
    public $challenge;

    public function updatedChallengeId()
    {
        $this->challenge = DBChallenge::with(
            'difficulty', 
            'status', 
            'visibility', 
            'tags:name', 
            'languages:name', 
            'frameworks:name', 
            'packages:name', 
            'topics:name',
            'creators'
        )
            ->whereId($this->challenge_id)
            ->first();
    }

    public function getChallenges()
    {
        $this->challenges = DBChallenge::with('topics:id,name')->select('id', 'title')->orderBy('title', 'asc')->get();
    }

    public function mount()
    {
        $this->current_route_name = request()->route()->getName();    // tackles livewire route name problem (livewire.update)
        $this->getChallenges();
    }

    public function render()
    {
        return view('livewire.admin.challenge')
            ->layout('layouts.app');
    }
}
