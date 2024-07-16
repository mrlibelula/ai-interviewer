<?php

namespace App\View\Components;

use App\Tool;
use Illuminate\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class AppLayout extends Component
{
    public array $models = [];
    public string $models_last_updated = '';
    public string $status_session_key = 'openai_status';

    public function __construct()
    {
        $this->getModels();
    }

    public function getModels()
    {
        $models_path = '/openai/models.json';
        if (Storage::exists($models_path)) {
            $storage_models = json_decode(Storage::get($models_path), true);
            $this->models = $storage_models['models'];
            $this->models_last_updated = $storage_models['updated_at'];
        } else {
            try {
                // this action may cost OpenAI tokens
                $this->models = Tool::getOpenAIModelsCompletion();
                Storage::put($models_path, json_encode([
                    'updated_at' => date('Y-m-d H:i:s'),
                    'models' => $this->models,
                ]));
    
            } catch (\OpenAI\Exceptions\ErrorException $e) {
                info($e->getMessage());
                dump($e->getMessage());
            }
        }
        $this->models = Tool::arraySortBy($this->models, 'id');

        $this->setSession();
    }

    public function setSession()
    {
        Session::put($this->status_session_key, false);
        if (count($this->models)) {
            Session::put($this->status_session_key, true);
            Session::put('openai_available_models', $this->models);
            Session::put('openai_models_last_updated', $this->models_last_updated);
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
