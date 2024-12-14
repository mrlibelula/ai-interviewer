<?php

namespace App\Livewire;

use App\Tool;
use App\Models\Topic;
use Livewire\Component;
use App\Models\Challenge;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class Welcome extends Component
{
    public $level = 1;
    public $solved_challenges;
    public $nb_challenges = 0;
    public $topics;
    public $available_ai_models = [];
    public $ai_models_last_updated = '';
    public $performance = [
        'success_rate' => 75,
        'completion_rate' => 60,
        'accuracy' => 85,
        'speed' => 40
    ];
    public $badges = [
        // Add your badges data
    ];
    public $recommended_challenges = [
        // Add your recommended challenges
    ];
    public $learning_resources = [
        // Add your learning resources
    ];
    public $community_stats = [
        'forum_posts' => 12,
        'solutions_shared' => 5,
        'forum_activity' => 65,
        'contribution_rate' => 40
    ];

    public function getAiModels()
    {
        $this->available_ai_models = session('openai_available_models') ?? [];
        $this->ai_models_last_updated = session('openai_models_last_updated') ?? '';
    }

    public function updateModelsList()
    {
        // updateModelsList in Session
        $models_path = '/openai/models.json';
        $updated_at = date('Y-m-d H:i:s');
        Session::put('openai_models_last_updated', $updated_at);
        $models = Tool::getOpenAIModelsCompletion();
        Session::put('openai_available_models', $models);
        Storage::put($models_path, json_encode([
            'updated_at' => $updated_at,
            'models' => $models,
        ]));
        $this->getAiModels();
        $this->available_ai_models = Tool::arraySortBy($this->available_ai_models, 'id');
        $this->dispatch('spinner-off');
    }

    public function getTopics()
    {
        // $this->topics = Topic::getTree();
    }

    public function mount()
    {
        $this->getAiModels();
        $this->getTopics();
        $this->nb_challenges = Challenge::select('id')->count();
        $this->solved_challenges = Tool::userSolvedChallenges(auth()->user());
    }

    public function render()
    {
        return view('livewire.welcome');
    }
}
