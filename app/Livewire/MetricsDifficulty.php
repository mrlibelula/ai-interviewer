<?php

namespace App\Livewire;

use App\Tool;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class MetricsDifficulty extends Component
{
    use WithPagination;
    
    protected Builder $builder;
    protected LengthAwarePaginator $tableChallenges;
    public $challenges;
    public int $nbChallenges = 0;
    public int $perPage = 3;
    public string $selectedDifficulty = 'easy';

    public float $easySuccessRate = 0;
    public float $mediumSuccessRate = 0;
    public float $hardSuccessRate = 0;

    public float $easyAverageTime = 0;
    public float $mediumAverageTime = 0;
    public float $hardAverageTime = 0;

    private function calculateSuccessRates(): void
    {
        $easyChallenges = Tool::userSolvedChallengesBuilder(auth()->user())
            ->where('difficulties.name', '=', 'easy')
            ->get();
        $nbEasyChallenges = Tool::challengesCountByDifficultyLevel('easy');
        $this->easySuccessRate = $nbEasyChallenges > 0 
            ? number_format(($easyChallenges->count() * 100) / $nbEasyChallenges, 0)
            : 0;

        $mediumChallenges = Tool::userSolvedChallengesBuilder(auth()->user())
            ->where('difficulties.name', '=', 'medium')
            ->get();
        $nbMediumChallenges = Tool::challengesCountByDifficultyLevel('medium');
        $this->mediumSuccessRate = $nbMediumChallenges > 0
            ? number_format(($mediumChallenges->count() * 100) / $nbMediumChallenges, 0)
            : 0;

        $hardChallenges = Tool::userSolvedChallengesBuilder(auth()->user())
            ->where('difficulties.name', '=', 'hard')
            ->get();
        $nbHardChallenges = Tool::challengesCountByDifficultyLevel('hard');
        $this->hardSuccessRate = $nbHardChallenges > 0
            ? number_format(($hardChallenges->count() * 100) / $nbHardChallenges, 0)
            : 0;

    }

    public function getChallengesByDifficulty(): void
    {
        $this->tableChallenges = $this->builder
            ->orderBy('title', 'ASC')
            ->orderBy('difficulty_id')
            ->where('difficulties.name', '=', $this->selectedDifficulty)
            ->paginate($this->perPage);
        
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
        $this->calculateSuccessRates();
        $this->calculateAvarageTimes();
        return view('livewire.metrics-difficulty', compact(['diffChallenges']));
    }

    private function calculateAvarageTimes(): void
    {
        $this->easyAverageTime = $this->calculateAvarageTime('easy');
        $this->mediumAverageTime = $this->calculateAvarageTime('medium');
        $this->hardAverageTime = $this->calculateAvarageTime('hard');
    }

    /**
     * Calculate the average time to solve a challenge in minutes
     * @param string $difficulty
     * @return float
     */
    private function calculateAvarageTime(string $difficulty = 'easy'): float
    {
        $solvedTimes = Tool::userSolvedChallengesBuilder(auth()->user())
            ->where('difficulties.name', '=', $difficulty)
            ->get()
            ->pluck('solved_time_seconds');
        return $solvedTimes->count() > 0 
            ? (float)number_format(($solvedTimes->sum() / $solvedTimes->count()) / 60, 2) 
            : 0;
    }

    public function getChartData(): array
    {
        return [
            'easy' => $this->easyAverageTime,
            'medium' => $this->mediumAverageTime,
            'hard' => $this->hardAverageTime
        ];
    }

    public function updatedPage()
    {
        $this->dispatch('difficulty-data-updated');
    }

    public function updatedPerPage()
    {
        $this->dispatch('difficulty-data-updated');
    }
}
