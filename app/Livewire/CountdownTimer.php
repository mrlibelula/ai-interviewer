<?php

namespace App\Livewire;

use Livewire\Component;

class CountdownTimer extends Component
{
    public $time_limit = '00:05:00';
    
    public function render()
    {
        return view('livewire.countdown-timer');
    }
}
