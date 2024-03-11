<?php

namespace App\Livewire;

use Livewire\Component;

class ChallengeCard extends Component
{
    public $challenge;
    
    public function render()
    {
        return view('livewire.challenge-card');
    }
}
