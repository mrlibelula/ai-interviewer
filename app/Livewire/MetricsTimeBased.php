<?php

namespace App\Livewire;

use App\Tool;
use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class MetricsTimeBased extends Component
{
    public $user;
    public $timeData;
    public $solvedByHour;
    public $solvedByDay;
    public $solvedByMonth;
    public $averageTimePerDifficulty;
    
    public function mount()
    {
        $this->user = auth()->user();
        $this->loadTimeMetrics();
    }

    protected function loadTimeMetrics()
    {
        // Get solved challenges grouped by hour (SQLite compatible)
        $this->solvedByHour = DB::table('challenge_solver')
            ->where('user_id', $this->user->id)
            ->whereNotNull('solved_at')
            ->select(DB::raw("strftime('%H', solved_at) as hour"), DB::raw('COUNT(*) as count'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Get solved challenges grouped by day (SQLite compatible)
        $this->solvedByDay = DB::table('challenge_solver')
            ->where('user_id', $this->user->id)
            ->whereNotNull('solved_at')
            ->select(DB::raw("strftime('%w', solved_at) as day_num"), DB::raw('COUNT(*) as count'))
            ->groupBy('day_num')
            ->orderBy('day_num')
            ->get()
            ->map(function($item) {
                $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                $item->day = $days[(int)$item->day_num];
                return $item;
            });

        // Get solved challenges grouped by month (SQLite compatible)
        $this->solvedByMonth = DB::table('challenge_solver')
            ->where('user_id', $this->user->id)
            ->whereNotNull('solved_at')
            ->select(DB::raw("strftime('%m', solved_at) as month_num"), DB::raw('COUNT(*) as count'))
            ->groupBy('month_num')
            ->orderBy('month_num')
            ->get()
            ->map(function($item) {
                $months = [
                    '01' => 'January', '02' => 'February', '03' => 'March',
                    '04' => 'April', '05' => 'May', '06' => 'June',
                    '07' => 'July', '08' => 'August', '09' => 'September',
                    '10' => 'October', '11' => 'November', '12' => 'December'
                ];
                $item->month = $months[$item->month_num];
                return $item;
            });

        // Get average completion time per difficulty (SQLite compatible)
        $this->averageTimePerDifficulty = DB::table('challenge_solver')
            ->join('challenges', 'challenge_solver.challenge_id', '=', 'challenges.id')
            ->join('difficulties', 'challenges.difficulty_id', '=', 'difficulties.id')
            ->where('challenge_solver.user_id', $this->user->id)
            ->whereNotNull('challenge_solver.solved_at')
            ->select(
                'difficulties.name as difficulty',
                DB::raw('AVG(solved_time_seconds) as avg_time'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('difficulties.name')
            ->orderBy('difficulties.id')
            ->get();
    }

    public function render()
    {
        return view('livewire.metrics-time-based');
    }
}