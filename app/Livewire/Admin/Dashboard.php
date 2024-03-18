<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Challenge;
use App\Models\Difficulty;
use App\Models\Status;
use App\Models\Topic;
use App\Models\Visibility;

class Dashboard extends Component
{
    public string $current_route_name;
    public int $nb_challenges = 0;
    public int $nb_topics = 0;
    public int $nb_active = 0;
    public int $nb_inactive = 0;
    public int $nb_archived = 0;
    public int $nb_easy = 0;
    public int $nb_medium = 0;
    public int $nb_hard = 0;
    public int $nb_public = 0;
    public int $nb_private = 0;
    
    public function mount()
    {
        $this->nb_challenges = Challenge::select('id')->count();
        $this->nb_topics = Topic::select('id')->where('parent_id', '=', null)->count();
        
        $status_active_id = Status::where('name', '=', 'active')->first()->id;
        $status_inactive_id = Status::where('name', '=', 'inactive')->first()->id;
        $status_archived_id = Status::where('name', '=', 'archived')->first()->id;
        $this->nb_active = Challenge::select('id', 'status_id')->where('status_id', '=', $status_active_id)->count();
        $this->nb_inactive = Challenge::select('id', 'status_id')->where('status_id', '=', $status_inactive_id)->count();
        $this->nb_archived = Challenge::select('id', 'status_id')->where('status_id', '=', $status_archived_id)->count();

        $easy_id = Difficulty::where('name', '=', 'easy')->first()->id;
        $medium_id = Difficulty::where('name', '=', 'medium')->first()->id;
        $hard_id = Difficulty::where('name', '=', 'hard')->first()->id;
        $this->nb_easy = Challenge::select('id', 'difficulty_id')->where('difficulty_id', '=', $easy_id)->count();
        $this->nb_medium = Challenge::select('id', 'difficulty_id')->where('difficulty_id', '=', $medium_id)->count();
        $this->nb_hard = Challenge::select('id', 'difficulty_id')->where('difficulty_id', '=', $hard_id)->count();

        $public_id = Visibility::where('name', '=', 'public')->first()->id;
        $private_id = Visibility::where('name', '=', 'private')->first()->id;
        $this->nb_public = Challenge::select('id', 'visibility_id')->where('visibility_id', '=', $public_id)->count();
        $this->nb_private = Challenge::select('id', 'visibility_id')->where('visibility_id', '=', $private_id)->count();
    }

    public function render()
    {
        $this->current_route_name = request()->route()->getName();

        return view('livewire.admin.dashboard');
    }
}
