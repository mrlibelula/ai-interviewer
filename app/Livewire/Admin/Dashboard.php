<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Dashboard extends Component
{
    public string $current_route_name;
    
    public function render()
    {
        $this->current_route_name = request()->route()->getName();
        return view('livewire.admin.dashboard')
            ->layout('layouts.app');
    }
}
