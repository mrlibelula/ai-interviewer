<?php

namespace App\Livewire;

use App\Models\Challenge;
use Livewire\Component;

class TopHeader extends Component
{
    public $query = '';
    public $searchResults = [];

    public function search()
    {
        $challenges = Challenge::where('title', 'like', '%' . $this->query . '%')
            ->orWhere('description', 'like', '%' . $this->query . '%')
            ->orWhere('test_cases', 'like', '%' . $this->query . '%') 
            ->orWhere('solution_code', 'like', '%' . $this->query . '%')
            ->with('difficulty')
            ->with('topics')
            ->get();

        $this->searchResults = $challenges;
    }

    public function clearSearch()
    {
        $this->query = '';
        $this->searchResults = [];
    }

    public function render()
    {
        return view('livewire.top-header');
    }
}
