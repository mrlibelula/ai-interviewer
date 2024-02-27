<?php

namespace App\Livewire;

use App\Models\Topic;
use Livewire\Component;

class Welcome extends Component
{
    public $level = 1;

    public function render()
    {
        $topics = Topic::getTree();
        return view('livewire.welcome', compact('topics'));
    }
}
