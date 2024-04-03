<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class Timer extends Component
{
    public $time_limit;
    public $target_time;
    public $timer;
    public bool $time_up = false;
    public string $time_up_message = '¯\_(ツ)_/¯';
    // public string $time_up_message = 'time is up!';
    public bool $start = false;

    public function mount(string $time_limit = '00:15:00')
    {
        $this->time_up = false;
        $this->target_time = Carbon::parse($time_limit);
        $this->timer = $this->calculateRemainingTime();
    }

    public function calculateRemainingTime()
    {
        $remaining = $this->target_time->format('H:i:s');
        if ($remaining === '00:00:00') {
            $this->time_up = true;
            return $this->time_up_message;
        } else {
            $this->target_time->subSecond();
            return $remaining;
        }
    }

    public function render()
    {
        return view('livewire.timer');
    }
}
