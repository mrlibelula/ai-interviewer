<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Topic;

class MetricsTopic extends Component
{
    public $topics;

    public function mount()
    {
        $this->topics = Topic::getFirstLevel();
    }

    public function render()
    {
        return view('livewire.metrics-topic');
    }
}
