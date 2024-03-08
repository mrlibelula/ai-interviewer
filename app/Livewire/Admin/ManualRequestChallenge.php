<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class ManualRequestChallenge extends Component
{
    public $challenge;
    public $is_new;
    
    public function render()
    {
        return view('livewire.admin.manual-request-challenge');
            // ->layout('layouts.app');
    }
}
