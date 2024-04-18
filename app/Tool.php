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
use App\Models\Enviro;
use App\Models\Visibility;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI;
use stdClass;

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
     * Prompts OpenAI and obtains array of completion messages
     *
     * @param array $messages
     * @return \OpenAI\Responses\Chat\CreateResponse
     */
    public static function getLLMCompletion(array $messages = ['role' => 'user', 'content' => 'hi']): \OpenAI\Responses\Chat\CreateResponse
    {
        $result = [];
        try {
            $completion = OpenAI::chat()->create([
                'model' => env('OPENAI_MODEL'), 
                'messages' => $messages, 
            ]);
            // info($messages);
            return $completion;
        } catch (\OpenAI\Exceptions\ErrorException $ee) {
            dump($ee->getMessage());
        } catch (\OpenAI\Exceptions\TransporterException $te) {
            dump($te->getMessage());
        }

        return $result;
    }

    /**
     * Obtains a LLM completion response from given prompt
     *
     * @param string $prompt
     * @return stdClass
     */
    public static function getLLMChallenge(string $prompt): stdClass
    {
        try {
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
            
            // for debugging purposes
            $info_debug_array = [
                'in_observation' => [
                    'completion_text_parts' => $completion_text_parts,
                    'comment' => 'It\'s still producing bug, null given on [0]. \\App\\Tool::177, log at \\App\\Tool::191'
                ],
            ];

            $completion_text_parts[0] = Tool::fixJsonString($completion_text_parts[0]);  // try to fix JSON response problems
            $challenge = json_decode($completion_text_parts[0] ?? 'n/a');
            
            // expected bug (usually openai timeout connections)
            if (!$challenge) {
                // $component->dispatch('spinner-off');
                info($info_debug_array);
                dump('Something went wrong while decoding challenge completion string. "$challenge" is null. Check app log. 🙊', $completion, $completion_text_parts, $completion_text_parts[0], $completion_text_parts[1], json_validate($completion_text_parts[0] ?? 'NULL'), json_decode($completion_text_parts[0] ?? 'n/a'), $challenge);
            }

            // emulated Challenge Model response property
            $emulated_challenge_model = new Challenge;
            $emulated_challenge_model->title = $challenge->title ?? 'n/a';
            $emulated_challenge_model->description = $challenge->challenge ?? 'n/a';
            $emulated_challenge_model->challenge_slug = Str::slug($challenge->title ?? 'n/a');
            $emulated_challenge_model->difficulty_id = Difficulty::where('name', 'like', '%' . $challenge->difficulty_level . '%')->first()->id;
            $emulated_challenge_model->test_cases = json_encode($challenge->test_cases);
            $emulated_challenge_model->hints = $challenge->hints;
            $emulated_challenge_model->time_limit = $challenge->time_limit;
            $emulated_challenge_model->status_id = Status::where('name', 'like', '%active%')->first()->id;
            $emulated_challenge_model->visibility_id = Visibility::where('name', 'like', '%public%')->first()->id;
            $emulated_challenge_model->solution_code = $completion_text_parts[1] ?? '';
            $emulated_challenge_model->chatgpt_prompt = $prompt;
            $emulated_challenge_model->completion_id = $completion->id;
            $emulated_challenge_model->ai_model = $completion->model;

            $result_llm_challenge = new stdClass;
            $result_llm_challenge->completion_text = $completion_text;
            $result_llm_challenge->completion = $completion;
            $result_llm_challenge->prompt = $prompt;
            $result_llm_challenge->challenge = $challenge;
            $result_llm_challenge->emulated_challenge_model = $emulated_challenge_model;

            return $result_llm_challenge;

        } catch (\OpenAI\Exceptions\ErrorException $ee) {
            dump($ee->getMessage());
        } catch (\OpenAI\Exceptions\TransporterException $te) {
            dump($te->getMessage());
        }
    }

    /**
     * Persists LLM challenge into local DB
     *
     * @param stdClass $llm_challenge
     * @param string $prompt
     * @param string $status
     * @param string $visibility
     * @return stdClass
     */
    public static function importAIChallenge(stdClass $llm_challenge, string $status = 'active', $visibility = 'public'): stdClass
    {
        $challenge = $llm_challenge->challenge;
        $completion = $llm_challenge->completion;
        $prompt = $llm_challenge->prompt;
        $completion_text = $llm_challenge->completion_text;
        $solution_code = $llm_challenge->emulated_challenge_model['solution_code'] ?? '';
        $challenge_slug = Str::slug($challenge->title ?? '');

        $challenge_db = Challenge::create([
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
            'completion_id' => $completion->id,
            'ai_model' => $completion->model,
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

            // assign creator/s
            $challenge_db->addCreator(auth()->user());

        }

        // Challenge::with('difficulty:id,name', 'status:id,name', 'visibility:id,name', 'tags:id,name', 'languages:id,name', 'frameworks:id,name', 'packages:id,name', 'topics:id,name', 'creators:id,name')->first()
        $final_challenge = Challenge::with(
            'difficulty', 
            'status', 
            'visibility', 
            'tags:name', 
            'languages:name', 
            'frameworks:name', 
            'packages:name', 
            'topics:name',
            'creators'
        )
            ->whereId($challenge_db->id)
            ->first();

        // info([
        //     'prompt' => $prompt, 
        //     'completion_text' => $completion_text, 
        //     'completion' => $completion, 
        // ]);

        $result_obj = new stdClass;
        $result_obj->challenge = $final_challenge;
        $result_obj->completion_text = $completion_text;
        $result_obj->completion = $completion;

        return $result_obj;
    }

    /**
     * Returns an array of Challenge 'titles' from DB that belongs to some Topic
     *
     * @param string $topic
     * @return array
     */
    public static function challengeTitlesByTopic(string $topic = 'all topics'): array
    {
        $builder = $topic !== 'all topics'
            ? Challenge::select('id', 'title')
                ->whereHas('topics', function ($query) use($topic) {
                    $query->where('name', 'like', '%' . $topic . '%');
                })
            : Challenge::select('id', 'title');

        return $builder->pluck('title')->toArray();
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

    /**
     * Get 'any' language or a specific one
     *
     * @param string $language
     * @return array
     */
    public static function getLanguages(string $language = 'any'): array
    {
        if (strtolower($language) === 'any') {
            return Language::select('id', 'name')->pluck('name')->toArray();
        } else {
            return Language::where('name', 'like', '%' . strtolower($language) . '%')->pluck('name')->toArray();
        }
    }

    /**
     * Returns enviro data from DB
     * if key = 'root', returns entire enviro object
     *
     * @param string $key
     * @param boolean $associative
     * @return array|stdClass|null
     */
    public static function enviro(string $key = 'root', bool $associative = true): array|stdClass|null
    {
        $enviro = Enviro::first();
        if ($enviro) {
            if ($key === 'root') return !$associative ? (object)$enviro->toArray() : $enviro->toArray();
            if (isset($enviro->$key)) {
                return $associative ? json_decode($enviro->$key, true) : json_decode($enviro->$key);
            }
        }
        return null;
    }

    /**
     * Removes trailing commas, and completes non-closed brackets from invalid JSON string
     * and returns a valid JSON or null if can't be fixed
     *
     * @param string $bad_json
     * @return string|null
     */
    public static function fixJsonString(string $bad_json): string|null
    {
        // complete non-closed brackets
        $fixed_json = self::fixJsonBracketsStack($bad_json);
        // removes trailing commas
        $fixed_json = preg_replace('/,\s*([\]}])/m', '$1', $fixed_json);

        if (json_validate($fixed_json)) {
            return $fixed_json;
        }
        return null;
    }

    public static function fixJsonBracketsStack(string $json_string): string
    {
        $stack = [];
        $len = strlen($json_string);
    
        // Iterate through each character of the string
        for ($i = 0; $i < $len; $i++) {
            $char = $json_string[$i];
    
            // If it's an opening bracket, push it onto the stack
            if ($char === '{' || $char === '[') {
                array_push($stack, $char);
            }
            // If it's a closing bracket, check if it matches the top of the stack
            elseif ($char === '}' || $char === ']') {
                // If the stack is empty or the closing bracket doesn't match the top of the stack, insert a missing opening bracket
                if (empty($stack) || ($char === '}' && end($stack) !== '{') || ($char === ']' && end($stack) !== '[')) {
                    // Insert missing opening bracket at the current position
                    $json_string = substr_replace($json_string, ($char === '}' ? '{' : '['), $i, 0);
                    // Adjust length and index for the inserted character
                    $len++;
                    $i++;
                } else {
                    // Pop the matching opening bracket from the stack
                    array_pop($stack);
                }
            }
        }
    
        // Add missing closing brackets for any remaining opening brackets on the stack
        while (!empty($stack)) {
            $json_string .= (end($stack) === '{' ? '}' : ']');
            array_pop($stack);
        }
    
        return $json_string;
    }

    /**
     * Repalces wildcards with provided data
     *
     * @param string $blueprint
     * @param string $selected_difficulty
     * @param string $selected_topic
     * @param string $selected_language
     * @return string
     */
    public static function wildcards(string $blueprint, string $selected_difficulty = 'medium', string $selected_topic = 'all topics', string $selected_language = 'any'): string
    {
        $topics = $selected_topic !== 'all topics'
            ? self::getTopics($selected_topic) 
            : self::getTopics();

        $languages = $selected_language !== 'any'
            ? self::getLanguages($selected_language)
            : self::getLanguages();

        $wildcards = collect([
            'separator' => env('OPENAI_CODE_SEPARATOR'), 
            'difficulty_level' => $selected_difficulty, 
            'topics' => json_encode($topics),
            'languages' => json_encode($languages),
            'tags' => json_encode(Tag::select('id', 'name')->pluck('name')->toArray()), 
            'dbchallenges' => json_encode(self::challengeTitlesByTopic($selected_topic)),
        ]);

        $wildcards->each(function ($wildcard, $key) use (&$blueprint) {
            $blueprint = self::regExWildcardReplacement($blueprint, $key, $wildcard);
        });

        return $blueprint;
    }

    /**
     * Replace wildcards on a blueprint GPT prompt
     *
     * @param string $blueprint
     * @param Collection $wildcards
     * @return string
     */
    public static function replaceWildcards(string $blueprint, Collection $wildcards): string
    {
        $wildcards->each(function ($wildcard, $key) use (&$blueprint) {
            $blueprint = self::regExWildcardReplacement($blueprint, $key, $wildcard);
        });

        return $blueprint;
    }
    
    /**
     * Regular expression " ??wildcard " replacement
     *
     * @param string $prompt
     * @param string $wildcard
     * @param string $replacement
     * @return string
     */
    public static function regExWildcardReplacement(string $prompt, string $wildcard, string $replacement): string
    {
        $pattern = '/\s\?\?' . strtolower($wildcard) . '\s/';
        return preg_replace($pattern, $replacement, $prompt);
    }

    /**
     * Code separator replacement
     *
     * @param string $prompt
     * @return string
     */
    public static function regExCodeSeparatorReplacement(string $prompt): string
    {
        return preg_replace('/\. (%+)(?:\.) /', '$1', $prompt);
    }

    /**
     * Updates 'enviro.prompt.string' with final GPT 'prompt' to be used
     *
     * @param string $prompt
     * @return boolean
     */
    public static function updateEnviroPromptString(string $prompt): bool
    {
        $enviro = Enviro::first();
        $enviro_prompt = json_decode($enviro->prompt, true);
        $enviro->prompt = json_encode([
            'parts' => self::searchSeparatorsAppendParts(),
            'string' => $prompt,
            'selected_topic' => $enviro_prompt['selected_topic'],
            'selected_difficulty' => $enviro_prompt['selected_difficulty'],
            'selected_language' => $enviro_prompt['selected_language'],
            'blueprint' => $enviro_prompt['blueprint'],
        ]);

        return $enviro->save();
    }

    /**
     * Search for 'separator/s' and append part/s to the main prompt array of strings
     * If no parts given, will be obtained from DB enviro prompt blueprint
     * Returns prompt parts
     *
     * @param array|null $parts
     * @return array
     */
    public static function searchSeparatorsAppendParts(array|null $parts = null): array
    {
        $final_parts = [];

        if (!$parts) {
            // obtain from DB
            $enviro_prompt_config = self::enviro('prompt'); 
            $prototype_prompt_base_text = $enviro_prompt_config['blueprint'];
            $parts = explode('. ', $prototype_prompt_base_text);
        }

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

        return $final_parts;
    }

    /**
     * Returns a Challenge Model
     *
     * @param integer $challenge_id
     * @param array $select
     * @param array $with
     * @param boolean $append_ai_solution
     * @return Challenge|null
     */
    public static function fetchChallenge(int $challenge_id, array $select = ['*'], array $with = [
        'difficulty', 
        'status', 
        'visibility', 
        'tags', 
        'languages', 
        'frameworks', 
        'packages', 
        'topics',
        'creators',
        'users',
    ], bool $append_ai_solution = false): Challenge|null
    {
        $challenge = Challenge::select(...$select)
            ->whereId($challenge_id)
            ->with(count($with) ? [...$with] : [])
            ->first();
        if (!$append_ai_solution) { if ($challenge) unset($challenge->solution_code); }
        return $challenge;
    }

    /**
     * Returns calculated bonus XP points based on
     * completion time and time threshold range
     *
     * @param integer $completion_time_seconds
     * @param integer $high_threshold_seconds
     * @param integer $medium_threshold_seconds
     * @return integer
     */
    public static function calculateBonusXP(int $completion_time_seconds, int $high_threshold_seconds = 300, int $medium_threshold_seconds = 600): int
    {
        $bonus_xp = 0;
        if ($completion_time_seconds < $high_threshold_seconds) {
            $bonus_xp = 20;
        } elseif ($completion_time_seconds < $medium_threshold_seconds) {
            $bonus_xp = 10;
        } else {
            $bonus_xp = 0;
        }
        return $bonus_xp;
    }

    /**
     * Returns the number of challenges within a difficulty level
     *
     * @param string $difficulty_level
     * @return integer|null
     */
    public static function challengesCountByDifficultyLevel(string $difficulty_level = 'easy'): int|null
    {
        return Difficulty::withCount('challenges')->where('name', strtolower($difficulty_level))->first()->challenges_count ?? 0;
    }

    /**
     * Returns all Challenges count
     *
     * @return integer
     */
    public static function challengesCount(): int
    {
        return Challenge::select('id')->count();
    }
    
    /**
     * Validate string time format "H:i:s"
     *
     * @param string $time_limit
     * @return boolean
     */
    public static function validateTimeLimitString(string $time_limit): bool
    {
        $pattern = '/^(?:[01]\d|2[0-3]):[0-5]\d:[0-5]\d$/';
        return preg_match($pattern, $time_limit);
    }

    /**
     * Removes line breaks "\n" and replaces them with "??" wildcard
     * and also removes triple tick code block and returns chars intead of HTML entities
     * Returns string with slashes in quotation marks if needed
     *
     * @param string $completion
     * @return string
     */
    public static function prepareAiAnswerString(string $completion): string
    {
        $completion = preg_replace('/\n/', '??', $completion);
        $completion = preg_replace('/```(javascript)?/', '', $completion);
        $return = htmlspecialchars_decode($completion);
        return addslashes($return);
    }
}