<?php

namespace App\Livewire;

use App\Tool;
use App\Models\Tag;
use App\Models\Topic;
use App\Models\Status;
use App\Models\Package;
use Livewire\Component;
use App\Models\Language;
use App\Models\Challenge;
use App\Models\Framework;
use App\Models\Difficulty;
use App\Models\Visibility;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI;

class Welcome extends Component
{
    public $level = 1;
    public $topics = [];
    public array $tags = [];
    protected $listeners = ['askGPT'];

    /**
     * Gets chatGPT challenge and stores it to DB
     *
     * @param string $difficulty_level
     * @return void
     */
    public function askGPT(string $difficulty_level = 'hard', string $topic = 'random'): void
    {
        try {
            $first_level_topics = collect($this->topics)->where('parent_id', null)->pluck('name');
            $topic_str = 'The topic of the challenge ';
            $topic_str .= $topic != 'random'
                ? 'is "' . $topic . '"'
                :' must be contained in this topics list "' . $first_level_topics . '". The challenge must focus on general programming concepts and problem-solving skills';

            $prompt = 'A code challenge commonly assessed in technical interviews. Give me your response in JSON format, example output format: { "title": "", "challenge": "", "difficulty_level": "easy|medium|hard", "time_limit": "H:i:s", "hints": "", "test_cases": ["", ""], "topics": ["", ""], tags: ["", ""], "languages": ["", ""], "frameworks": ["", ""], "packages": ["", ""] }¿¿¿¿¿solution_code. The difficulty level must be "' . $difficulty_level . '". The "solution_code" area must contain the code with the latest standard recommendations (es6, psr7, pep8, etc.) and must be after "¿¿¿¿¿" characters. The "frameworks", "packages", "test_cases" and "languages" props can be empty arrays. Append at least one language to languages array. ' . $topic_str . '. The selected "tags" must be contained in this tags list: "' . json_encode($this->tags) . '". No line break between json and solution code. Double check the solution code';

            $messages = [
                [
                    'role' => 'user', 
                    'content' => $prompt,
                ], 
            ];

            $completion = OpenAI::chat()->create([
                'model' => env('OPENAI_MODEL'), 
                'messages' => $messages, 
            ]);
            
            $completion_text = $completion->choices[0]->message->content;

            $completion_text_parts = explode('¿¿¿¿¿', $completion_text);
            $challenge = json_decode($completion_text_parts[0] ?? 'n/a');
            $solution_code = $completion_text_parts[1] ?? '';
            $challenge_slug = Str::slug($challenge->title ?? '');

            $challenge_db = Challenge::firstOrCreate([
                'title' => $challenge->title,
                'description' => $challenge->challenge,
                'challenge_slug' => $challenge_slug,
                'difficulty_id' => Difficulty::select('id')->where('name', '=', $challenge->difficulty_level)->first()->id,
                'test_cases' => json_encode($challenge->test_cases),
                'hints' => $challenge->hints,
                'time_limit' => $challenge->time_limit,
                'status_id' => Status::select('id')->where('name', '=', 'active')->first()->id,
                'visibility_id' => Visibility::select('id')->where('name', '=', 'public')->first()->id,
                'solution_code' => $solution_code,
            ]);

            if ($challenge_db->wasRecentlyCreated) {
                $challenge_id = $challenge_db->id;

                // assign topic/s
                $topics = [];
                foreach ($challenge->topics as $challenge_topic) {
                    $topics[] = Topic::select('id', 'name')->where('name', 'like', '%' . $challenge_topic . '%')->first();
                }
                
                if (count($topics)) {
                    foreach ($topics as $topic) {
                        if ($topic) $challenge_db->addTopic($topic);
                    }
                }
        
                // assign framework/s
                $frameworks = [];
                foreach ($challenge->frameworks as $challenge_fw) {
                    $frameworks[] = Framework::select('id', 'name')->where('name', 'like', '%' . $challenge_fw .'%')->first();
                }
        
                if (count($frameworks)) {
                    foreach ($frameworks as $framework) {
                        if ($framework) $challenge_db->addFramework($framework);
                    }
                }
        
                // assign language/s
                $languages = [];
                foreach ($challenge->languages as $challenge_lang) {
                    $languages[] = Language::select('id', 'name')->where('name', 'like', '%'. $challenge_lang .'%')->first();
                }
        
                if (count($languages)) {
                    foreach ($languages as $language) {
                        if ($language) $challenge_db->addLanguage($language);
                    }
                }
        
                // assign package/s
                $packages = [];
                foreach ($challenge->packages as $challenge_package) {
                    $packages[] = Package::select('id', 'name')->where('name', 'like', '%'. $challenge_package .'%')->first();
                }
        
                if (count($packages)) {
                    foreach ($packages as $package) {
                        if ($package) $challenge_db->addPackage($package);
                    }
                }

                // assign tags/s
                $tags = [];
                foreach ($challenge->tags as $challenge_tag) {
                    $tags[] = Tag::select('id', 'name')->where('name', 'like', '%'. $challenge_tag .'%')->first();
                }
        
                if (count($tags)) {
                    foreach ($tags as $tag) {
                        if ($tag) $challenge_db->addTag($tag);
                    }
                }
            }

            dd(Challenge::with('difficulty', 'status', 'visibility', 'tags:name', 'languages:name', 'frameworks:name', 'packages:name', 'topics:name')
                ->first(), $prompt, $completion_text);

            
        } catch (\OpenAI\Exceptions\ErrorException $ee) {
            Tool::toastr($this, 'error', [
                'title' => 'OpenAI Error',
                'message' => $ee->getMessage(),
            ]);
        } catch (\OpenAI\Exceptions\TransporterException $te) {
            Tool::toastr($this, 'error', [
                'title' => 'OpenAI Error',
                'message' => $te->getMessage(),
            ]);
        }

    }

    public function render()
    {
        $this->tags = Tag::all()->pluck('name')->toArray();
        $this->topics = Topic::getTree();
        
        return view('livewire.welcome');
    }
}
