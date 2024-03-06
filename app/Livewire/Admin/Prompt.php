<?php

namespace App\Livewire\Admin;

use App\Models\Difficulty;
use App\Models\Tag;
use App\Models\Topic;
use App\Models\Enviro;
use App\Tool;
use Livewire\Component;

class Prompt extends Component
{
    public string $currentRouteName;
    public array $prompt_parts = [];
    public string $selected_topic = 'all topics';
    public string $selected_difficulty = 'easy';
    public string $build_json = '';
    public array $json_array = [];
    public array $json_keys = [];
    public array $json_values = [];
    public array $topics = [];
    public array $difficulties = [];
    public string $prompt = '';

    public function buildChallengePrompt()
    {
        $this->buildJson();
        $prompt = implode('. ', $this->prompt_parts);
        $prompt = $this->regExCodeSeparatorReplacement($prompt);

        $topics = $this->selected_topic != 'all topics'
            ? Tool::getTopics($this->selected_topic) 
            : Tool::getTopics();
        
        $wildcards = collect([
            'separator' => env('OPENAI_CODE_SEPARATOR'), 
            'difficulty_level' => $this->selected_difficulty, 
            'topics' => json_encode($topics),
            'tags' => json_encode(Tag::pluck('name')->toArray()), 
        ]);

        $wildcards->each(function ($wildcard, $key) use (&$prompt) {
            $prompt = $this->regExWildcardReplacement($prompt, $key, $wildcard);
        });

        $this->prompt = $prompt;

        // save data to db
        $enviro = Enviro::first();
        $enviro->prompt = json_encode([
            'parts' => $this->prompt_parts,
            'string' => $this->prompt,
        ]);
        $enviro->save();
    }

    /**
     * code separator replacement
     *
     * @param string $prompt
     * @return string
     */
    public function regExCodeSeparatorReplacement(string $prompt): string
    {
        return preg_replace('/\. (%+)(?:\.) /', '$1', $prompt);
    }

    /**
     * ??wildcard replacement
     *
     * @param string $prompt
     * @param string $wildcard
     * @param string $replacement
     * @return string
     */
    public function regExWildcardReplacement(string $prompt, string $wildcard, string $replacement): string
    {
        $pattern = '/\s\?\?' . strtolower($wildcard) . '\s/';
        return preg_replace($pattern, $replacement, $prompt);
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
        $final_parts = [];

        // search for 'separator/s' and append part/s to the main prompt array of strings
        collect($parts)->each(function ($string) use(&$final_parts) {
            $string_parts = explode(env('OPENAI_CODE_SEPARATOR'), $string);
            if (count($string_parts)) {
                $counter = 0;
                foreach ($string_parts as $str) {
                    $final_parts[] = $str;
                    $counter++;
                    count($string_parts) === $counter
                        ? //last
                        : $final_parts[] = env('OPENAI_CODE_SEPARATOR');
                }
            } else {
                $final_parts[] = $string;
            }
        });

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
        
        $db_prompt_parts = json_decode($enviro->prompt)->parts;
        $this->prompt_parts = $db_prompt_parts;

        // propagate enviro data into local props
        $this->build_json = json_validate( $db_prompt_parts[1]) ?  $db_prompt_parts[1] : '{}';

        $this->buildJsonArrays();
        $this->buildJson();
        $this->buildChallengePrompt();
    }

    public function getDifficulties()
    {
        $this->difficulties = [];
        $this->difficulties = Difficulty::all()->pluck('name')->toArray();
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
        $this->currentRouteName = request()->route()->getName();    // tackles livewire route name problem (livewire.update)
        $this->getTopLevelTopics();
        $this->getDifficulties();
        $this->loadBlueprintDataAndStoreToDB();
    }

    public function render()
    {
        return view('livewire.admin.prompt')
            ->layout('layouts.app');
    }
}
