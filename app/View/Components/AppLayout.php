<?php

namespace App\View\Components;

use Illuminate\View\View;
use Illuminate\View\Component;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class AppLayout extends Component
{
    public array $models = [];
    public string $status_session_key = 'openai_status';

    public function __construct()
    {
        $this->getModels();
    }

    public function getModels()
    {
        $models_path = '/openai/models.json';
        if (Storage::exists($models_path)) {
            $this->models = json_decode(Storage::get($models_path), true);
        } else {
            try {
                $this->models = OpenAI::models()->list()->toArray()['data'];
            } catch (\OpenAI\Exceptions\ErrorException $e) {
                info($e->getMessage());
            }
            Storage::put($models_path, json_encode($this->models));
        }
        $this->setSession();
    }
    
    public function setSession()
    {
        Session::put($this->status_session_key, false);
        if (count($this->models)) {
            Session::put($this->status_session_key, true);
            Session::put('openai_available_models', $this->models);
        }
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
