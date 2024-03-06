<?php

namespace App;

use App\Models\Tag;
use App\Models\Topic;
use App\Models\Status;
use App\Models\Package;
use App\Models\Language;
use App\Models\Challenge;
use App\Models\Framework;
use App\Models\Difficulty;
use App\Models\Visibility;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI;
use Livewire\Component as LivewireComponent;
use Illuminate\View\Component as ViewComponent;

class Tool
{
    /**
     * Returns a random array item
     *
     * @param array $array
     * @return mixed
     */
    public static function randomItem(array $array): mixed
    {
        if (!empty($array)) {
            return $array[rand(0, count($array) - 1)];
        }
    }

    /**
     * base64 URL encode
     *
     * @param string $data
     * @return string
     */
    public static function base64url_encode(string $data) : string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * base64 URL decode
     *
     * @param string $data
     * @return string
     */
    public static function base64url_decode(string $data) : string
    {
        // return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4));
    }

    /**
     * Encrypt encode ('base_64' or 'laravel')
     *
     * @param string $data, bool $method = 'base_64'
     * @return string
     */
    public static function encode(string $data, string $method = 'base_64') : string
    {
        if ($method == 'base_64') {
            return self::base64url_encode($data);
        } else if($method == 'laravel') { // laravel internal encoder
            return encrypt($data);
        } else {
            return self::base64url_encode($data);
        }
    }
    
    /**
     * Encrypt decode ('base_64' or 'laravel')
     *
     * @param string $data, bool $method = 'base_64'
     * @return string
     */
    public static function decode(string $data, string $method = 'base_64') : string
    {
        if ($method == 'base_64') {
            return self::base64url_decode($data);
        } else if ($method == 'laravel') { // laravel internal encoder
            return decrypt($data);
        } else {
            return self::base64url_decode($data);
        }
    }

    // /**
    //  * Dispatches a Toastr event from '$component'
    //  * with specific message type
    //  * and ['title', 'message'] array
    //  * 
    //  * Listeners must be installed in main layout
    //  *
    //  * @param LivewireComponent|ViewComponent $component
    //  * @param string $type
    //  * @param array $message_array
    //  * @return void
    //  */
    // public static function toastr(LivewireComponent|ViewComponent $component, string $type = 'info', array $message_array): void
    // {
    //     $component->dispatch($type, [ 
    //         'title' => $message_array['title'] ?? '',
    //         'message' => $message_array['message'] ?? 'No message', 
    //     ]);
    // }

    /**
     * Finds an array item inside an array
     * by given key and value
     * and returns that [key => value] pair
     *
     * @param array $array_of_arrays
     * @param string $find_key
     * @param [type] $find_value
     * @return array
     */
    public static function findItemByKey(array $array_of_arrays, string $find_key, $find_value): array
    {
        foreach ($array_of_arrays as $key => $array) {
            if (array_key_exists($find_key, $array)) {
                if ($array[$find_key] == $find_value) {
                    return collect([$key => $array])->first();
                }
            }
        }
        return [];
    }

    /**
     * Updates or creates JSON properties values and returns updated JSON string
     *
     * @param string $original_json_data
     * @param array $updated_data_array
     * @return string|false
     */
    public static function updateOrCreateJsonColumns(string $original_json_data, array $updated_data_array): string|false
    {
        $updated_json_columns = json_decode($original_json_data, true);
        foreach ($updated_data_array as $column => $new_value) {
            $updated_json_columns[$column] = $new_value;
        }
        $updated_json_columns = json_encode($updated_json_columns);
        return $updated_json_columns;
    }

    /**
     * Gets chatGPT challenge and stores it into DB
     *
     * @param string $difficulty_level
     * @param string $topic
     * @return Challenge
     */
    public static function getLLMChallenge(string $prompt, string $status = 'active', string $visibility = 'public'): Challenge
    {
        try {
            // $first_level_topics = Topic::where('parent_id', '=', null)->pluck('name');
            // $tags = Tag::all()->pluck('name')->toArray();
            // $topic_str = 'The topic of the challenge ';
            // $topic_str .= strtolower($topic) != 'random'
            //     ? 'is "' . ucfirst($topic) . '"'
            //     : ' must be contained in this topics list "' . $first_level_topics . '". The challenge must focus on general programming concepts and problem-solving skills';

            // $prompt = 'A code challenge commonly assessed in technical interviews. Give me your response in JSON format, example output format: { "title": "", "challenge": "", "difficulty_level": "easy|medium|hard", "time_limit": "H:i:s", "hints": "", "test_cases": ["", ""], "topics": ["", ""], tags: ["", ""], "languages": ["", ""], "frameworks": ["", ""], "packages": ["", ""] }%%%%%solution_code. The difficulty level must be "' . $difficulty_level . '". The "solution_code" area must contain the code with the latest standard recommendations (es6, psr7, pep8, etc.) and must be after "%%%%%" characters. The "frameworks", "packages", "test_cases" and "languages" props can be empty arrays. Append at least one language to languages array. ' . $topic_str . '. The selected "tags" must be contained in this tags list: "' . json_encode($tags) . '". No line break between json and solution_code. Double check the solution_code';

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

            $completion_text_parts = explode(env('OPENAI_CODE_SEPARATOR'), $completion_text);
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
                'status_id' => Status::select('id')->where('name', '=', $status)->first()->id,
                'visibility_id' => Visibility::select('id')->where('name', '=', $visibility)->first()->id,
                'solution_code' => $solution_code,
                'chatgpt_prompt' => $prompt,
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

            $final_challenge = Challenge::with(
                    'difficulty', 
                    'status', 
                    'visibility', 
                    'tags:name', 
                    'languages:name', 
                    'frameworks:name', 
                    'packages:name', 
                    'topics:name'
                )
                ->whereId($challenge_db->id)
                ->first();

            info([
                'prompt' => $prompt, 
                'completion_text' => $completion_text, 
                'completion' => $completion, 
            ]);

            return $final_challenge;

            
        } catch (\OpenAI\Exceptions\ErrorException $ee) {
            // Tool::toastr($this, 'error', [
            //     'title' => 'OpenAI Error',
            //     'message' => $ee->getMessage(),
            // ]);
            info($ee->getMessage());
        } catch (\OpenAI\Exceptions\TransporterException $te) {
            // Tool::toastr($this, 'error', [
            //     'title' => 'OpenAI Error',
            //     'message' => $te->getMessage(),
            // ]);
            info($te->getMessage());
        }

    }

    /**
     * Get 'all topic' names or just 'one topic' name
     *
     * @param string $topic
     * @return array
     */
    public static function getTopics(string $topic = 'all_topics'): array
    {
        if (strtolower($topic) === 'all_topics') {
            return Topic::where('parent_id', '=', null)->pluck('name')->toArray();
        } else {
            return Topic::where('name', 'like', '%' . strtolower($topic) . '%')->pluck('name')->toArray();
        }
    }
}