<?php

namespace App\Livewire;

use App\Models\User;
use App\Tool;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class MetricsComparison extends Component
{
    use WithPagination;

    public $sortField = 'solved_challenges';
    public $sortDirection = 'desc';
    public $searchTerm = '';
    public $timeRange = 'all'; // all, week, month, year
    
    public function sortBy($field)
    {
        $this->sortDirection = $this->sortField === $field 
            ? $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc'
            : 'asc';

        $this->sortField = $field;
    }

    public function getUsersPerformanceData()
    {
        $query = DB::table('users')
            ->leftJoin('challenge_solver', 'users.id', '=', 'challenge_solver.user_id')
            ->select(
                'users.id',
                'users.name',
                DB::raw('COUNT(DISTINCT CASE WHEN challenge_solver.solved_at IS NOT NULL THEN challenge_solver.challenge_id END) as solved_challenges'),
                DB::raw('SUM(challenge_solver.bonus_xp) as total_bonus_xp'),
                DB::raw('SUM(challenge_solver.extra_bonus) as total_extra_bonus'),
                DB::raw('AVG(challenge_solver.solved_time_seconds) as avg_completion_time')
            )
            ->where('users.name', 'like', '%' . $this->searchTerm . '%')
            ->groupBy('users.id', 'users.name');

        // Apply time range filter
        if ($this->timeRange !== 'all') {
            $query->where('challenge_solver.solved_at', '>=', match($this->timeRange) {
                'week' => now()->subWeek(),
                'month' => now()->subMonth(),
                'year' => now()->subYear(),
                default => now()->subCentury()
            });
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        return $query->paginate(10);
    }

    public function render()
    {
        return view('livewire.metrics-comparison', [
            'users' => $this->getUsersPerformanceData(),
            'totalChallenges' => Tool::challengesCount()
        ]);
    }
}
