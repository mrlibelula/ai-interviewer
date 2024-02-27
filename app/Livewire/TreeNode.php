<?php

namespace App\Livewire;

use Livewire\Component;

class TreeNode extends Component
{
    public $topic;
    public $level;

    public function mount($topic, $level)
    {
        $this->topic = $topic;
        $this->level = $level;
    }
    
    public function render()
    {
        return view('livewire.tree-node');
    }
}
