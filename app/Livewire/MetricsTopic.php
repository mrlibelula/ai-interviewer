<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Topic;
use App\Models\Challenge;
use App\Models\Attempt;
use Illuminate\Support\Facades\DB;

class MetricsTopic extends Component
{
    public $topicLabels;
    public $topicCounts;
    public $avgAttempts;
    public $avgCompletionTimes;
    public $mostPopularTopic;
    public $highestAttemptTopic;
    public $fastestCompletionTopic;

    public function mount()
    {
        $this->loadMetrics();
    }

    private function loadMetrics()
    {
        // Get first level topics with their challenges and user-specific metrics
        $topics = Topic::getFirstLevel()
            ->map(function($topic) {
                $topic->load(['challenges' => function($query) {
                    $query->join('challenge_solver', 'challenges.id', '=', 'challenge_solver.challenge_id')
                        ->where('challenge_solver.user_id', auth()->id()) // Only get current user's data
                        ->select(
                            'challenges.id',
                            DB::raw('AVG(challenge_solver.attempts) as avg_attempts'),
                            DB::raw('AVG(challenge_solver.solved_time_seconds) as avg_completion_time'),
                            DB::raw('COUNT(DISTINCT challenges.id) as solved_count') // Count solved challenges
                        )
                        ->groupBy('challenges.id');
                }]);
                return $topic;
            });

        $this->topicLabels = $topics->pluck('name')->toArray();
        
        // Count of solved challenges per topic for the user
        $this->topicCounts = $topics->map(function($topic) {
            return $topic->challenges->sum('solved_count');
        })->toArray();
        
        // User's average attempts per topic
        $this->avgAttempts = $topics->map(function($topic) {
            return $topic->challenges->avg('avg_attempts') ?? 0;
        })->toArray();

        // User's average completion time per topic
        $this->avgCompletionTimes = $topics->map(function($topic) {
            return $topic->challenges->avg('avg_completion_time') ?? 0;
        })->toArray();

        // Get user's most solved topic
        $this->mostPopularTopic = $topics->sortByDesc(function($topic) {
            return $topic->challenges->sum('solved_count');
        })->first()->name ?? 'None';

        // Get user's topic with highest attempt rate
        $maxAttemptsTopic = $topics->sortByDesc(function($topic) {
            return $topic->challenges->avg('avg_attempts') ?? 0;
        })->first();
        $this->highestAttemptTopic = $maxAttemptsTopic->name ?? 'None';

        // Get user's fastest completion topic
        $fastestTopic = $topics->sortBy(function($topic) {
            return $topic->challenges->avg('avg_completion_time') ?? PHP_FLOAT_MAX;
        })->first();
        $this->fastestCompletionTopic = $fastestTopic->name ?? 'None';
    }

    public function render()
    {
        return view('livewire.metrics-topic');
    }
}
