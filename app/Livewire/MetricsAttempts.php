<?php

namespace App\Livewire;

use App\Models\User;
use App\Tool;
use Livewire\Component;
use Livewire\WithPagination;

class MetricsAttempts extends Component
{
    use WithPagination;

    public $user;
    public $perPage = 5;

    public function mount()
    {
        $this->user = auth()->user();
    }

    public function render()
    {
        $solved_challenges = Tool::userSolvedChallengesMetrics(
            $this->user,
            $this->perPage
        );

        // Calculate total attempts from all challenges (not just current page)
        $total_attempts = $solved_challenges->sum('attempts');
        
        // Calculate average attempts (using total number of challenges, not just current page)
        $average_attempts = $solved_challenges->total() > 0 
            ? number_format($total_attempts / $solved_challenges->total(), 1) 
            : 0;

        return view('livewire.metrics-attempts', [
            'solved_challenges' => $solved_challenges,
            'total_attempts' => $total_attempts,
            'average_attempts' => $average_attempts,
        ]);
    }
}
