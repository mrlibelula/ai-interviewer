<?php

namespace App\Livewire;

use App\Tool;
use Carbon\CarbonInterval;
use Livewire\Component;

class ChallengeCard extends Component
{
    public $challenge;
    public bool $header = true;
    public bool $title = true;
    public bool $footer = true;
    public bool $creators = true;
    
    /**
     * Returns time_limit for humans
     *
     * @param string $time_limit
     * @return string
     */
    public function timeLimit(string $time_limit): string
    {
        $time = Tool::validateTimeLimitString($time_limit) 
            ? CarbonInterval::createFromFormat('H:i:s', $time_limit)
            : CarbonInterval::createFromFormat('H:i:s', '00:00:00');
        return $time->forHumans();
    }

    public function render()
    {
        return view('livewire.challenge-card');
    }
}
