<?php

namespace App\Livewire\Admin;

use App\Models\Challenge;
use App\Models\Difficulty;
use App\Models\Tag;
use App\Models\Topic;
use App\Models\Enviro;
use App\Models\Language;
use App\Tool;
use Livewire\Component;

class Prompt extends Component
{
    public string $current_route_name;
    public array $prompt_parts = [];
    public string $selected_topic = 'all topics';
    public string $selected_difficulty = 'easy';
    public $selected_language;
    public string $build_json = '';
    public array $json_array = [];
    public array $json_keys = [];
    public array $json_values = [];
    public array $topics = [];
    public array $difficulties = [];
    public array $languages = [];
    public string $blueprint = '';
    public string $prompt = '';

    public function buildChallengePrompt()
    {
        $this->buildJson();
        $prompt = implode('. ', $this->prompt_parts);
        $prompt = Tool::regExCodeSeparatorReplacement($prompt);

        // final blueprint
        $this->blueprint = $prompt;

        // replace all wildcards and generate final prompt
        $this->prompt = Tool::wildcards($this->blueprint, $this->selected_difficulty, $this->selected_topic, $this->selected_language);

        // save data to DB
        $enviro = Enviro::first();
        $enviro->prompt = json_encode([
            'parts' => $this->prompt_parts,
            'string' => $this->prompt,
            'selected_topic' => $this->selected_topic,
            'selected_difficulty' => $this->selected_difficulty,
            'selected_language' => $this->selected_language,
            'blueprint' => $this->blueprint,
        ]);
        $enviro->save();
    }

    public function buildJson()
    {
        $json = [];
        foreach ($this->json_keys as $key => $value) {
            $json[$value] = json_decode($this->json_values[$key], true);
        }
        $this->build_json = json_encode($json);
    }

    public function updated()
    {
        $this->buildChallengePrompt();
    }

    public function buildJsonArrays()
    {
        $this->json_keys = [];
        $this->json_values = [];

        $this->json_array = json_decode($this->build_json, true);
        foreach ($this->json_array as $key => $value) {
            $this->json_keys[] = $key;
            $this->json_values[] = json_encode($value);
        }
    }

    /**
     * Loads data from .env and creates or updates DB
     * if isNew is false, will use DB data in the UI
     * if isNew is true, will use new/updated data from .env
     *
     * @param boolean $isNew
     * @return void
     */
    public function loadBlueprintDataAndStoreToDB(bool $isNew = false): void
    {
        $prototype_prompt_base_text = env('OPENAI_PROMPT_BASE_TEXT');
        $parts = explode('. ', $prototype_prompt_base_text);
        
        // search for 'separator/s' and append part/s to the main prompt array of strings
        $final_parts = Tool::searchSeparatorsAppendParts($parts);

        // First or create enviro data
        $enviro = Enviro::first();
        if (!$enviro) {
            $enviro = Enviro::firstOrCreate([
                'prompt' => json_encode(['parts' => $final_parts]),
            ]);
        } else {
            if ($isNew) {
                $enviro->prompt = json_encode(['parts' => $final_parts]);
                $enviro->save();
            }
        }

        // propagate enviro data into local props
        $db_prompt = json_decode($enviro->prompt);
        $this->prompt = $db_prompt->string ?? '';
        $this->selected_topic = $db_prompt->selected_topic ?? 'all topics';
        $this->selected_difficulty = $db_prompt->selected_difficulty ?? 'easy';
        $this->selected_language = $db_prompt->selected_language ?? 'any';
        $this->blueprint = $db_prompt->blueprint ?? '';
        $db_prompt_parts = $db_prompt->parts;
        $this->prompt_parts = $db_prompt_parts;


        // JSON single option (multiple JSONs in the future)
        foreach ($db_prompt_parts as $part) {
            if (json_validate($part)) {
                $this->build_json = $part;
                break;
            } else {
                $this->build_json = '{}';
            }
        }

        $this->buildJsonArrays();
        $this->buildJson();
        $this->buildChallengePrompt();
    }

    public function getDifficulties()
    {
        $this->difficulties = [];
        $this->difficulties = Difficulty::all()->pluck('name')->toArray();
    }

    public function getLanguages()
    {
        $this->languages = [];
        $this->languages = Language::all()->pluck('name')->toArray();
    }

    public function getTopLevelTopics()
    {
        $this->topics = [];
        $this->topics[] = [0 => 'All topics'];
        $this->topics[] = Topic::where('parent_id', '=', null)->pluck('name', 'id')->toArray();
        $this->topics = collect($this->topics)->flatten()->toArray();
    }

    public function mount()
    {
        $this->current_route_name = request()->route()->getName();    // tackles livewire route name problem (livewire.update)
        $this->getTopLevelTopics();
        $this->getDifficulties();
        $this->getLanguages();
        $this->loadBlueprintDataAndStoreToDB();
    }

    public function render()
    {
        return view('livewire.admin.prompt')
            ->layout('layouts.app');
    }
}
