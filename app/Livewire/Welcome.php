<?php

namespace App\Livewire;

use App\Models\Topic;
use Livewire\Component;

class Welcome extends Component
{
    public function render()
    {
        $topics = Topic::getTree();
        return view('livewire.welcome', compact('topics'));
    }
}
