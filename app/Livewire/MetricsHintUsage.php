<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;

class MetricsHintUsage extends Component
{
    protected function pivotChatMessages($pivot): array
    {
        $settings = $pivot->openai_chat_settings;
        if (is_array($settings)) {
            return $settings['messages'] ?? [];
        }
        if (is_string($settings) && $settings !== '') {
            return json_decode($settings, true)['messages'] ?? [];
        }

        return [];
    }

    public function getHintChartData()
    {
        $hintCounts = [
            'easy' => 0,
            'medium' => 0,
            'hard' => 0
        ];
        
        $challenges = auth()->user()->challenges;
        
        foreach ($challenges as $challenge) {
            $difficulty = strtolower($challenge->difficulty->name);
            $chatMessages = $this->pivotChatMessages($challenge->pivot);
            
            $userMessages = array_filter($chatMessages, function($message) {
                return $message['role'] === 'user' && !empty(trim($message['content']));
            });
            
            $hintCounts[$difficulty] += count($userMessages);
        }
        
        return $hintCounts;
    }

    public function getAverageHints()
    {
        $averages = ['easy' => 0, 'medium' => 0, 'hard' => 0];
        $counts = ['easy' => 0, 'medium' => 0, 'hard' => 0];
        
        foreach (auth()->user()->challenges as $challenge) {
            $difficulty = strtolower($challenge->difficulty->name);
            $chatMessages = $this->pivotChatMessages($challenge->pivot);
            $hintCount = count(array_filter($chatMessages, fn($msg) => 
                $msg['role'] === 'user' && !empty(trim($msg['content']))
            ));
            
            $averages[$difficulty] += $hintCount;
            $counts[$difficulty]++;
        }
        
        foreach ($averages as $diff => $total) {
            $averages[$diff] = $counts[$diff] ? round($total / $counts[$diff], 1) : 0;
        }
        
        return $averages;
    }

    public function getTimelineData($difficulty)
    {
        // Get all challenges for this difficulty level
        $challenges = auth()->user()->challenges()
            ->with('difficulty')
            ->whereHas('difficulty', function($query) use ($difficulty) {
                $query->where('name', strtolower($difficulty));
            })
            ->orderBy('solved_at')
            ->get();

        // Get all dates from timeline labels to ensure alignment
        $allDates = collect($this->getTimelineLabels())
            ->map(fn($label) => Carbon::parse($label)->format('Y-m-d'))
            ->flip()
            ->map(fn() => 0)
            ->toArray();

        // Process each challenge
        foreach ($challenges as $challenge) {
            $date = Carbon::parse($challenge->solved_at)->format('Y-m-d');
            if (isset($allDates[$date])) {
                $messages = $this->pivotChatMessages($challenge->pivot);
                
                $hintCount = count(array_filter($messages, function($msg) {
                    return isset($msg['role']) && 
                           $msg['role'] === 'user' && 
                           !empty(trim($msg['content'] ?? ''));
                }));

                $allDates[$date] += $hintCount;
            }

            // Debug each challenge processing
            // info("Processing challenge for $difficulty:", [
            //     'date' => $date,
            //     'hint_count' => $hintCount ?? 0,
            //     'chat_settings' => $chatSettings ?? null,
            //     'messages_count' => count($messages ?? [])
            // ]);
        }

        // info("Real hint data for $difficulty:", [
        //     'user_id' => auth()->id(),
        //     'challenge_count' => $challenges->count(),
        //     'hints_by_date' => $allDates,
        //     'dates' => array_keys($allDates),
        //     'counts' => array_values($allDates)
        // ]);

        return array_values($allDates);
    }

    public function getTimelineLabels()
    {
        return auth()->user()->challenges()
            ->orderBy('solved_at')
            ->pluck('solved_at')
            ->map(function($date) {
                return Carbon::parse($date)->format('M d');
            })
            ->unique()
            ->values();
    }

    public function getStats()
    {
        $stats = [];
        foreach (['easy', 'medium', 'hard'] as $difficulty) {
            $challenges = collect(auth()->user()->challenges)
                ->where('difficulty.name', strtolower($difficulty));
                
            $stats[$difficulty] = [
                'avg_hints' => $this->getAverageHints()[$difficulty],
                'completion_rate' => $challenges->count() ? 
                    round(($challenges->where('status', 'completed')->count() / $challenges->count()) * 100) : 0
            ];
        }
        return $stats;
    }

    public function render()
    {
        return view('livewire.metrics-hint-usage', [
            'stats' => $this->getStats()
        ]);
    }
}
