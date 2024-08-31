<?php

namespace App\Livewire;

use App\Tool;
use Livewire\Component;
use Livewire\WithPagination;

class MetricsDifficulty extends Component
{
    use WithPagination;
    
    protected \Illuminate\Database\Query\Builder $builder;
    protected \Illuminate\Contracts\Pagination\LengthAwarePaginator $tableChallenges;
    public $challenges;
    public int $nbChallenges = 0;
    public int $per_page = 3;
    public string $selectedDifficulty = 'easy';

    public function getChallengesByDifficulty(): void
    {
        $this->tableChallenges = $this->builder
            ->orderBy('title', 'ASC')
            ->orderBy('difficulty_id')
            ->where('difficulties.name', '=', $this->selectedDifficulty)
            ->paginate($this->per_page);
        
        $this->challenges = $this->builder
            ->get()
            ->sortBy('title')
            ->sortBy('difficulty_id')
            ->where('difficulty_name', '=', $this->selectedDifficulty);
        
        $this->nbChallenges = $this->challenges->count();
        $this->challenges->map(function ($challenge) { unset($challenge->solution_code); });
    }

    public function queryBuilder()
    {
        $this->builder = Tool::userSolvedChallengesBuilder(auth()->user());
    }

    public function updatingSelectedDifficulty()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        $this->queryBuilder();
        $this->getChallengesByDifficulty('easy');
        $diffChallenges = $this->tableChallenges;
        return view('livewire.metrics-difficulty', compact(['diffChallenges']));
    }
}
