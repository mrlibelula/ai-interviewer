<?php

namespace App\Livewire;

use App\Tool;
use App\Models\Topic;
use Livewire\Component;
use App\Models\Challenge;

class Welcome extends Component
{
    public $level = 1;
    public $solved_challenges;
    public $nb_challenges = 0;
    public $perc_solved = 0;
    public $topics;

    public function getTopics()
    {
        $this->topics = Topic::getTree();
    }

    public function mount()
    {
        $this->getTopics();
        $this->nb_challenges = Challenge::select('id')->count();
        $this->solved_challenges = Tool::userSolvedChallenges(auth()->user());
        $this->perc_solved = Tool::percentageSolved($this->solved_challenges->count(), $this->nb_challenges);
    }

    public function render()
    {
        return view('livewire.welcome');
    }
}
