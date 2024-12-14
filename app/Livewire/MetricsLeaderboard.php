<?php

namespace App\Livewire;

use App\Models\User;
use App\Tool;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class MetricsLeaderboard extends Component
{
    public $sortBy = 'xp'; // Default sorting
    public $timeFrame = 'all'; // all, week, month

    public function render()
    {
        $users = User::query()
            ->withCount(['challenges as solved_count' => function($query) {
                $query->whereNotNull('solved_at')
                    ->when($this->timeFrame === 'week', function ($q) {
                        return $q->where('solved_at', '>=', now()->subWeek());
                    })
                    ->when($this->timeFrame === 'month', function ($q) {
                        return $q->where('solved_at', '>=', now()->subMonth());
                    });
            }])
            ->withSum(['challenges as total_xp' => function($query) {
                $query->whereNotNull('solved_at')
                    ->when($this->timeFrame === 'week', function ($q) {
                        return $q->where('solved_at', '>=', now()->subWeek());
                    })
                    ->when($this->timeFrame === 'month', function ($q) {
                        return $q->where('solved_at', '>=', now()->subMonth());
                    });
            }], DB::raw('bonus_xp + extra_bonus'))
            ->when($this->sortBy === 'xp', function ($query) {
                return $query->orderByDesc('total_xp');
            })
            ->when($this->sortBy === 'completions', function ($query) {
                return $query->orderByDesc('solved_count');
            })
            ->when($this->sortBy === 'efficiency', function ($query) {
                return $query->orderByDesc(DB::raw('total_xp / NULLIF(solved_count, 0)'));
            })
            ->take(10)
            ->get();

        return view('livewire.metrics-leaderboard', [
            'users' => $users
        ]);
    }

    public function updateSort($sortBy)
    {
        $this->sortBy = $sortBy;
    }

    public function updateTimeFrame($timeFrame)
    {
        $this->timeFrame = $timeFrame;
    }
}
