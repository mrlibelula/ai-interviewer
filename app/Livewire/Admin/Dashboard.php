<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Dashboard extends Component
{
    public string $currentRouteName;
    public function render()
    {
        $this->currentRouteName = request()->route()->getName();
        return view('livewire.admin.dashboard')
            ->layout('layouts.app');
    }
}
