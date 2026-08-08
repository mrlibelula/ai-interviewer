<?php

namespace App;

use stdClass;
use Exception;
use Carbon\Carbon;
use App\Models\Tag;
use App\Models\User;
use App\Models\Topic;
use App\Models\Enviro;
use App\Models\Status;
use Livewire\Component as LivewireComponent;
use App\Models\Package;
use App\Models\Language;
use App\Models\Challenge;
use App\Models\Framework;
use App\Models\Difficulty;
use App\Models\Visibility;
use Illuminate\Support\Str;
use Illuminate\View\Component as ViewComponent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Http;

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

    /**
     * Dispatches a Toastr event from '$component'
     * with specific message type
     * and ['title', 'message'] array
     * 
     * Listeners must be installed in main layout
     *
     * @param LivewireComponent|ViewComponent $component
     * @param string $type
     * @param array $message_array
     * @return void
     */
    public static function toastr(LivewireComponent|ViewComponent $component, array $message_array, string $type = 'info'): void
    {
        $component->dispatch($type, [ 
            'title' => $message_array['title'] ?? '',
            'message' => $message_array['message'] ?? 'No message', 
        ]);
    }

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
     * Finds an object item inside an array
     * by given property and value
     *
     * @param array $array_of_objects
     * @param string $prop
     * @param $value
     * @return stdClass
     */
    public static function findObjectByProp(array $array_of_objects, string $prop, $value): stdClass
    {
        foreach ($array_of_objects as $object) {
            if (property_exists($object, $prop)) {
                if ($object->$prop == $value) {
                    return $object;
                }
            }
        }
        return new stdClass;
    }

    /**
     * Sort array by key
     *
     * @param array $array
     * @param string $sort_by
     * @param string $order_by
     * @param boolean $case_sensitive
     * @return array
     */
    public static function arraySortBy(array $array, string $sort_by, string $order_by = 'asc', bool $case_sensitive = false): array
    {
        return (array)collect($array)->sortBy([
            strtolower($order_by) === 'asc' 
                ? fn ($a, $b) => ($case_sensitive ? $a[$sort_by] : strtolower($a[$sort_by])) <=> ($case_sensitive ? $b[$sort_by] : strtolower($b[$sort_by]))
                : fn ($a, $b) => ($case_sensitive ? $b[$sort_by] : strtolower($b[$sort_by])) <=> ($case_sensitive ? $a[$sort_by] : strtolower($a[$sort_by]))
        ])->values()->all();
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
     * @return \OpenAI\Responses\Chat\CreateResponse|string
     */
    public const PROMPT_TEMPLATE_KEYS = [
        'welcome',
        'recommendations',
        'challenge_system',
        'analyze_user_code',
        'complexity_analysis',
        'feedback',
        'dalle',
        'challenge_generation',
    ];

    /**
     * Resolve a prompt template: Enviro.prompt_templates → config/openai_prompts (includes .env).
     */
    public static function promptTemplate(string $key): string
    {
        $enviro = Enviro::first();
        $templates = $enviro?->prompt_templates;

        if (is_array($templates) && isset($templates[$key]) && is_string($templates[$key]) && $templates[$key] !== '') {
            $prompt = $templates[$key];
        } else {
            $prompt = (string) config('openai_prompts.' . $key, '');
        }

        if ($key === 'analyze_user_code') {
            return self::sanitizeAnalyzeUserCodePrompt($prompt);
        }

        return $prompt;
    }

    /**
     * Default prompt templates for admin seeding / AI settings UI.
     */
    public static function defaultPromptTemplates(): array
    {
        $defaults = [];
        foreach (self::PROMPT_TEMPLATE_KEYS as $key) {
            $defaults[$key] = (string) config('openai_prompts.' . $key, '');
        }

        return $defaults;
    }

    /**
     * JSON schema for structured challenge generation (includes solution_code).
     */
    public static function challengeOutputSchema(): array
    {
        return [
            'type' => 'object',
            'additionalProperties' => false,
            'properties' => [
                'title' => ['type' => 'string'],
                'challenge' => ['type' => 'string'],
                'difficulty_level' => ['type' => 'string', 'enum' => ['easy', 'medium', 'hard']],
                'time_limit' => [
                    'type' => 'string',
                    'description' => 'Interview time limit in H:i:s format only, e.g. 00:30:00',
                ],
                'hints' => ['type' => 'string'],
                'test_cases' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'topics' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'tags' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'languages' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'frameworks' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'packages' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'solution_code' => ['type' => 'string'],
            ],
            'required' => [
                'title',
                'challenge',
                'difficulty_level',
                'time_limit',
                'hints',
                'test_cases',
                'topics',
                'tags',
                'languages',
                'frameworks',
                'packages',
                'solution_code',
            ],
        ];
    }

    /**
     * JSON schema for code analysis (feedback + solved flag).
     */
    public static function codeAnalysisOutputSchema(): array
    {
        return [
            'type' => 'object',
            'additionalProperties' => false,
            'properties' => [
                'feedback' => ['type' => 'string'],
                'solved' => ['type' => 'boolean'],
            ],
            'required' => ['feedback', 'solved'],
        ];
    }

    /**
     * Strip legacy "%%%%%true|false" instructions from analyze prompts.
     * Older .env / enviro templates still tell the model to append a separator,
     * which then leaks into structured-output `feedback` text.
     */
    public static function sanitizeAnalyzeUserCodePrompt(string $prompt): string
    {
        $prompt = preg_replace(
            '/Immediat(?:e)?ly after your answer,\s*put a\s*["\']?%{4,}["\']?\s*\([^)]*\)\s*separator[^.]*\.\s*/i',
            '',
            $prompt
        ) ?? $prompt;

        $prompt = preg_replace(
            '/put a\s*["\']?%{4,}["\']?\s*\([^)]*\)\s*separator[^.]*\.\s*/i',
            '',
            $prompt
        ) ?? $prompt;

        $prompt = preg_replace(
            '/If the user has solved the challenge return\s*["\']?true["\']?,?\s*otherwise return\s*["\']?false["\']?\.?\s*/i',
            '',
            $prompt
        ) ?? $prompt;

        $suffix = ' Put the approval verdict only in the JSON boolean field "solved". Never append a legacy separator trailer or bare true/false after the feedback text. Respond using the required structured JSON schema (feedback + solved).';

        if (!preg_match('/Never append a legacy separator|Put the approval verdict only in the JSON boolean/i', $prompt)) {
            $prompt = rtrim($prompt) . $suffix;
        } elseif (!preg_match('/required structured JSON schema/i', $prompt)) {
            $prompt = rtrim($prompt) . ' Respond using the required structured JSON schema (feedback + solved).';
        }

        return trim(preg_replace('/[ \t]{2,}/', ' ', $prompt) ?? $prompt);
    }

    /**
     * Parse code-analysis completion: structured JSON preferred, legacy separator fallback.
     * Always strips a leaked "%%%%%true|false" trailer from feedback text.
     *
     * @return array{feedback: string, solved: bool}
     */
    public static function parseCodeAnalysisResponse(string $completionContent): array
    {
        $separator = (string) env('OPENAI_CODE_SEPARATOR', '%%%%%');
        $feedback = '';
        $solved = false;

        $parsed = json_decode($completionContent, true);

        if (is_array($parsed) && array_key_exists('feedback', $parsed)) {
            $feedback = trim((string) $parsed['feedback']);
            $solved = filter_var($parsed['solved'] ?? false, FILTER_VALIDATE_BOOLEAN);
        } else {
            $parts = $separator !== ''
                ? explode($separator, $completionContent, 2)
                : [$completionContent];
            $feedback = trim($parts[0] ?? '');
            $solved = filter_var(strtolower(trim($parts[1] ?? 'false')), FILTER_VALIDATE_BOOLEAN);
        }

        if ($separator !== '' && str_contains($feedback, $separator)) {
            $parts = explode($separator, $feedback, 2);
            $feedback = trim($parts[0] ?? '');
            if (isset($parts[1]) && preg_match('/^\s*(true|false)\s*$/i', $parts[1])) {
                $solved = filter_var(strtolower(trim($parts[1])), FILTER_VALIDATE_BOOLEAN);
            }
        }

        return [
            'feedback' => $feedback,
            'solved' => (bool) $solved,
        ];
    }

    /**
     * Structured-output response_format wrapper for json_schema.
     */
    public static function jsonSchemaResponseFormat(string $name, array $schema): array
    {
        return [
            'type' => 'json_schema',
            'json_schema' => [
                'name' => $name,
                'strict' => true,
                'schema' => $schema,
            ],
        ];
    }

    public static function getLLMCompletion(array $messages = ['role' => 'user', 'content' => 'hi'], ?array $responseFormat = null): \OpenAI\Responses\Chat\CreateResponse|string
    {
        try {
            $payload = [
                'model' => env('OPENAI_MODEL'),
                'messages' => $messages,
            ];

            if ($responseFormat) {
                $payload['response_format'] = $responseFormat;
            }

            return OpenAI::chat()->create($payload);
        } catch (\OpenAI\Exceptions\ErrorException $ee) {
            info($ee->getMessage());
            $error = $ee->getMessage();
        } catch (\OpenAI\Exceptions\TransporterException $te) {
            info($te->getMessage());
            $error = $te->getMessage();
        }

        return $error;
    }

    /**
     * Strip legacy "no newlines in solution_code" rules and require readable multi-line code.
     * Older enviro prompts banned "\n" in every JSON value (including solution_code), which
     * makes newer models minify solutions into one line that the codebox cannot format.
     */
    public static function sanitizeChallengeGenerationPrompt(string $prompt): string
    {
        $prompt = preg_replace(
            '/None of the JSON values must contain line breaks\s*"\\\\n"\s*neither the solution code\.?\s*/i',
            '',
            $prompt
        ) ?? $prompt;

        $prompt = preg_replace(
            '/None of the JSON values must contain line breaks[^.]*\.?\s*/i',
            '',
            $prompt
        ) ?? $prompt;

        $prompt = preg_replace(
            '/No line breaks between JSON and solution_code\.?\s*/i',
            '',
            $prompt
        ) ?? $prompt;

        $prompt = preg_replace(
            '/Do not include "solution_code" key in your JSON response[^.]*\.?\s*/i',
            '',
            $prompt
        ) ?? $prompt;

        $suffix = ' Important: Put solution_code in the JSON response as readable multi-line source with real newline characters and normal indentation. Never minify or collapse solution_code into a single line.';

        if (!preg_match('/solution_code MUST be readable multi-line|Never minify or collapse solution_code/i', $prompt)) {
            $prompt = rtrim($prompt) . $suffix;
        }

        return $prompt;
    }

    /**
     * Normalize LLM solution_code for display (real newlines, expand minified brace-language code).
     */
    public static function normalizeSolutionCode(?string $code): string
    {
        $code = (string) ($code ?? '');
        $code = str_replace(["\r\n", "\r"], "\n", $code);

        // json_decode normally turns JSON "\n" into real newlines; guard double-escaped forms
        if (substr_count($code, "\n") === 0 && str_contains($code, '\\n')) {
            $code = str_replace(['\\n', '\\t'], ["\n", "\t"], $code);
        }

        $code = trim($code);

        // Minified LLM output often starts with `// comment ... function foo(){...}` on one line.
        // Without a break, `//` would comment out the entire solution.
        $code = self::breakLineCommentBeforeStatement($code);

        // Models sometimes still return a single dense line; expand C-like / JS-like source for the codebox.
        if ($code !== '' && substr_count($code, "\n") === 0 && preg_match('/[{;}]/', $code)) {
            $code = self::expandMinifiedBraceCode($code);
        } elseif ($code !== '' && substr_count($code, "\n") > 0) {
            // Comment was split onto its own line; still expand the remaining dense statement block.
            $lines = explode("\n", $code);
            $expanded = [];
            foreach ($lines as $line) {
                $trim = trim($line);
                    if (
                    $trim !== ''
                    && !str_starts_with($trim, '//')
                    && substr_count($trim, "\n") === 0
                    && preg_match('/[{;}]/', $trim)
                    && (strlen($trim) > 60 || substr_count($trim, '{') > 0)
                ) {
                    $expanded[] = self::expandMinifiedBraceCode($trim);
                } else {
                    $expanded[] = $line;
                }
            }
            $code = implode("\n", $expanded);
        }

        return trim($code);
    }

    /**
     * If a single-line blob starts with // and later has a statement keyword, break before it.
     */
    public static function breakLineCommentBeforeStatement(string $code): string
    {
        if ($code === '' || substr_count($code, "\n") > 0) {
            return $code;
        }

        if (!str_starts_with(ltrim($code), '//')) {
            return $code;
        }

        if (preg_match(
            '/^(\/\/.*?)(?=\b(?:function|const|let|var|class|export|async|def|public|private|protected)\b)(.*)$/s',
            $code,
            $matches
        )) {
            return rtrim($matches[1]) . "\n" . ltrim($matches[2]);
        }

        return $code;
    }

    /**
     * Lightweight pretty-printer for minified brace languages (JS, PHP-ish, C-like).
     * Not a full formatter — enough to make admin codebox readable.
     */
    public static function expandMinifiedBraceCode(string $code): string
    {
        $out = '';
        $indent = 0;
        $paren = 0;
        $len = strlen($code);
        $inSingle = false;
        $inDouble = false;
        $inBacktick = false;
        $inLineComment = false;
        $inBlockComment = false;
        $escape = false;

        $newline = function () use (&$out, &$indent) {
            $out = rtrim($out, " \t");
            $out .= "\n" . str_repeat('  ', max(0, $indent));
        };

        for ($i = 0; $i < $len; $i++) {
            $ch = $code[$i];
            $next = $i + 1 < $len ? $code[$i + 1] : '';

            if ($inLineComment) {
                $out .= $ch;
                if ($ch === "\n") {
                    $inLineComment = false;
                }
                continue;
            }

            if ($inBlockComment) {
                $out .= $ch;
                if ($ch === '*' && $next === '/') {
                    $out .= '/';
                    $i++;
                    $inBlockComment = false;
                }
                continue;
            }

            if ($inSingle || $inDouble || $inBacktick) {
                $out .= $ch;
                if ($escape) {
                    $escape = false;
                    continue;
                }
                if ($ch === '\\') {
                    $escape = true;
                    continue;
                }
                if ($inSingle && $ch === "'") {
                    $inSingle = false;
                } elseif ($inDouble && $ch === '"') {
                    $inDouble = false;
                } elseif ($inBacktick && $ch === '`') {
                    $inBacktick = false;
                }
                continue;
            }

            if ($ch === '/' && $next === '/') {
                $inLineComment = true;
                $out .= '//';
                $i++;
                continue;
            }

            if ($ch === '/' && $next === '*') {
                $inBlockComment = true;
                $out .= '/*';
                $i++;
                continue;
            }

            if ($ch === "'") {
                $inSingle = true;
                $out .= $ch;
                continue;
            }
            if ($ch === '"') {
                $inDouble = true;
                $out .= $ch;
                continue;
            }
            if ($ch === '`') {
                $inBacktick = true;
                $out .= $ch;
                continue;
            }

            if ($ch === '(') {
                $paren++;
                $out .= $ch;
                continue;
            }

            if ($ch === ')') {
                $paren = max(0, $paren - 1);
                $out .= $ch;
                continue;
            }

            if ($ch === '{') {
                $out .= '{';
                $indent++;
                $newline();
                continue;
            }

            if ($ch === '}') {
                $indent = max(0, $indent - 1);
                $newline();
                $out .= '}';
                if ($next === ';' || $next === ',') {
                    $out .= $next;
                    $i++;
                    $newline();
                } elseif ($next !== '' && $next !== ')' && $next !== ']' && $next !== ',') {
                    $newline();
                }
                continue;
            }

            if ($ch === ';') {
                $out .= ';';
                // Keep for (;;;) headers on one line.
                if ($paren === 0 && $next !== '}') {
                    $newline();
                }
                continue;
            }

            $out .= $ch;
        }

        // Collapse excessive blank lines from consecutive breaks
        $out = preg_replace("/\n{3,}/", "\n\n", $out) ?? $out;

        return trim($out);
    }

    /**
     * Obtains a LLM completion response from given prompt.
     * Returns null on API/timeout/decode failures (never dumps into the HTTP response).
     *
     * @param string $prompt
     * @return stdClass|null
     */
    public static function getLLMChallenge(string $prompt): ?stdClass
    {
        try {
            $prompt = self::sanitizeChallengeGenerationPrompt($prompt);

            $messages = [
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ];

            $completion = self::getLLMCompletion(
                $messages,
                self::jsonSchemaResponseFormat('llm_challenge', self::challengeOutputSchema())
            );

            if (!$completion instanceof \OpenAI\Responses\Chat\CreateResponse) {
                info([
                    'getLLMChallenge' => 'LLM completion failed',
                    'error' => is_string($completion) ? $completion : 'LLM challenge completion failed',
                ]);
                return null;
            }

            $completion_text = $completion->choices[0]->message->content;
            $challenge = json_decode($completion_text ?? '');

            // Legacy fallback: separator + fixJsonString (pre-structured-output models / failures)
            if (!$challenge && str_contains((string) $completion_text, (string) env('OPENAI_CODE_SEPARATOR', '%%%%%'))) {
                $completion_text_parts = explode(env('OPENAI_CODE_SEPARATOR'), $completion_text);
                $completion_text_parts[0] = Tool::fixJsonString($completion_text_parts[0] ?? '');
                $challenge = json_decode($completion_text_parts[0] ?? 'n/a');
                if ($challenge && empty($challenge->solution_code)) {
                    $challenge->solution_code = $completion_text_parts[1] ?? '';
                }
            }

            if (!$challenge) {
                info([
                    'getLLMChallenge' => 'Failed to decode structured challenge JSON',
                    'completion_text' => $completion_text,
                ]);
                return null;
            }

            $challenge->solution_code = self::normalizeSolutionCode($challenge->solution_code ?? '');

            $difficulty = Difficulty::where('name', 'like', '%' . ($challenge->difficulty_level ?? '') . '%')->first();
            $status = Status::where('name', 'like', '%active%')->first();
            $visibility = Visibility::where('name', 'like', '%public%')->first();

            if (!$difficulty || !$status || !$visibility) {
                info([
                    'getLLMChallenge' => 'Missing difficulty/status/visibility lookup',
                    'difficulty_level' => $challenge->difficulty_level ?? null,
                ]);
                return null;
            }

            $emulated_challenge_model = new Challenge;
            $emulated_challenge_model->title = $challenge->title ?? 'n/a';
            $emulated_challenge_model->description = $challenge->challenge ?? 'n/a';
            $emulated_challenge_model->challenge_slug = Str::slug($challenge->title ?? 'n/a');
            $emulated_challenge_model->difficulty_id = $difficulty->id;
            $emulated_challenge_model->test_cases = is_string($challenge->test_cases ?? null)
                ? $challenge->test_cases
                : json_encode($challenge->test_cases ?? []);
            $emulated_challenge_model->hints = $challenge->hints ?? '';
            $emulated_challenge_model->time_limit = self::normalizeTimeLimit($challenge->time_limit ?? null);
            $emulated_challenge_model->status_id = $status->id;
            $emulated_challenge_model->visibility_id = $visibility->id;
            $emulated_challenge_model->solution_code = self::normalizeSolutionCode($challenge->solution_code ?? '');
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
            info(['getLLMChallenge' => $ee->getMessage()]);
            return null;
        } catch (\OpenAI\Exceptions\TransporterException $te) {
            info(['getLLMChallenge' => $te->getMessage()]);
            return null;
        } catch (Exception $e) {
            info(['getLLMChallenge' => $e->getMessage()]);
            return null;
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
        $solution_code = self::normalizeSolutionCode(
            $llm_challenge->emulated_challenge_model->solution_code
                ?? $llm_challenge->challenge->solution_code
                ?? ''
        );
        $challenge_slug = Str::slug($challenge->title ?? '');

        // // generate an AI image (DALL-E) about the challenge
        // $banner_url = self::generateChallengeImage($challenge->title, $challenge->topics[0] ?? '', $challenge->languages[0] ?? '');
        $banner_url = null;

        $challenge_db = Challenge::create([
            'title' => $challenge->title,
            'description' => $challenge->challenge,
            'challenge_slug' => $challenge_slug,
            'difficulty_id' => Difficulty::select('id')->where('name', '=', $challenge->difficulty_level)->first()->id,
            'test_cases' => json_encode($challenge->test_cases),
            'hints' => $challenge->hints,
            'time_limit' => self::normalizeTimeLimit($challenge->time_limit ?? null),
            'status_id' => Status::select('id')->where('name', '=', $status)->first()->id,
            'visibility_id' => Visibility::select('id')->where('name', '=', $visibility)->first()->id,
            'solution_code' => $solution_code,
            'chatgpt_prompt' => $prompt,
            'completion_id' => $completion->id,
            'ai_model' => $completion->model,
            'banner_url' => $banner_url,
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
            if (isset($challenge->frameworks)) {
                foreach ($challenge->frameworks as $challenge_fw) {
                    $frameworks[] = Framework::select('id', 'name')->where('name', 'like', '%' . $challenge_fw .'%')->first();
                }
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
     * Generate an AI image (DALL-E) about the challenge
     *
     * @param string $challenge_title
     * @param string $challenge_topic
     * @param string $language
     * @return string
     */
    public static function generateChallengeImage(string $challenge_title, string $challenge_topic, string $language): string
    {
        $image_url = '';
        $api_key = env('OPENAI_API_KEY');
        $end_point = 'https://api.openai.com/v1/images/generations';
        
        try {
            $prompt = Tool::replaceWildcards(self::promptTemplate('dalle'), collect([
                'challenge_title' => $challenge_title,
                'challenge_topic' => $challenge_topic,
                'language' => $language,
            ]));
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $api_key
            ])->post($end_point, [
                'prompt' => $prompt,
                //'max_tokens' => 64, // Adjust as needed
                //'temperature' => 0.7, // Adjust as needed
                'n' => 1, // Number of completions to generate
                'size' => "512x512",
            ]);
            $response_data = $response->json();
            $image_url = $response_data['data'][0]['url'];
        } catch (Exception $e) {
            info(['error' => $e->getMessage()]);
        }
        return $image_url;
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
                $value = $enviro->$key;
                if (is_array($value)) {
                    return $associative ? $value : json_decode(json_encode($value));
                }
                if (is_string($value)) {
                    return $associative ? json_decode($value, true) : json_decode($value);
                }

                return $associative ? (array) $value : (object) $value;
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
     * and returns the final generated prompt
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
        $enviro_prompt = is_array($enviro->prompt) ? $enviro->prompt : (json_decode($enviro->prompt, true) ?? []);
        $enviro->prompt = [
            'parts' => self::searchSeparatorsAppendParts(),
            'string' => $prompt,
            'selected_topic' => $enviro_prompt['selected_topic'] ?? 'all topics',
            'selected_difficulty' => $enviro_prompt['selected_difficulty'] ?? 'easy',
            'selected_language' => $enviro_prompt['selected_language'] ?? 'any',
            'blueprint' => $enviro_prompt['blueprint'] ?? '',
        ];

        return $enviro->save();
    }

    /**
     * Search for 'separator/s' and append part/s to the main prompt array of strings. 
     * If no parts given, will be obtained from DB enviro prompt blueprint. 
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
     * completion time, time threshold range
     * and 'solved_time_seconds'
     *
     * @param integer $completion_time_seconds
     * @param integer $high_threshold_seconds
     * @param integer $medium_threshold_seconds
     * @return array
     */
    public static function calculateBonusXP(int $completion_time_seconds, int $high_threshold_seconds = 300, int $medium_threshold_seconds = 600): array
    {
        $bonus_xp = 0;
        $extra_bonus = 0;
        
        if ($completion_time_seconds < $high_threshold_seconds) {
            $bonus_xp = 20;
            $extra_bonus = (int)(($high_threshold_seconds - $completion_time_seconds) * 0.1);
        } elseif ($completion_time_seconds < $medium_threshold_seconds) {
            $bonus_xp = 10;
            $extra_bonus = (int)(($medium_threshold_seconds - $completion_time_seconds) * 0.05);
        }
        
        // Ensure extra_bonus is never less than zero
        $extra_bonus = max(0, $extra_bonus - $bonus_xp);

        return [
            'bonus_xp' => $bonus_xp,
            'extra_bonus' => $extra_bonus
        ];
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
     * Returns all Challenges count on db
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
        return (bool) preg_match($pattern, $time_limit);
    }

    /**
     * Normalize LLM / free-form time limits to "H:i:s".
     * Accepts "00:30:00", "30 minutes", "45m", "1 hour", "1h 30m", or minutes as an integer string.
     */
    public static function normalizeTimeLimit(?string $timeLimit, string $default = '00:30:00'): string
    {
        $raw = trim((string) $timeLimit);
        if ($raw === '') {
            return $default;
        }

        if (preg_match('/^(?:[01]?\d|2[0-3]):[0-5]?\d(?::[0-5]?\d)?$/', $raw)) {
            $parts = array_map('intval', explode(':', $raw));
            $hours = $parts[0] ?? 0;
            $minutes = $parts[1] ?? 0;
            $seconds = $parts[2] ?? 0;
            if ($hours <= 23 && $minutes <= 59 && $seconds <= 59) {
                return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
            }
        }

        if (preg_match('/^(\d+)\s*(?:h|hr|hrs|hour|hours)\s+(\d+)\s*(?:m|min|mins|minute|minutes)$/i', $raw, $match)) {
            $hours = (int) $match[1];
            $minutes = (int) $match[2];
            if ($hours <= 23 && $minutes <= 59) {
                return sprintf('%02d:%02d:00', $hours, $minutes);
            }
        }

        if (preg_match('/^(\d+)\s*(?:h|hr|hrs|hour|hours)$/i', $raw, $match)) {
            $hours = (int) $match[1];
            if ($hours <= 23) {
                return sprintf('%02d:00:00', $hours);
            }
        }

        if (preg_match('/^(\d+)\s*(?:m|min|mins|minute|minutes)$/i', $raw, $match)) {
            $totalMinutes = (int) $match[1];
            $hours = intdiv($totalMinutes, 60);
            $minutes = $totalMinutes % 60;
            if ($hours <= 23) {
                return sprintf('%02d:%02d:00', $hours, $minutes);
            }
        }

        if (preg_match('/^\d+$/', $raw)) {
            $totalMinutes = (int) $raw;
            $hours = intdiv($totalMinutes, 60);
            $minutes = $totalMinutes % 60;
            if ($hours <= 23) {
                return sprintf('%02d:%02d:00', $hours, $minutes);
            }
        }

        return $default;
    }

    /**
     * @return array{hours: int, minutes: int, seconds: int}
     */
    public static function timeLimitParts(?string $timeLimit): array
    {
        $parts = explode(':', self::normalizeTimeLimit($timeLimit));

        return [
            'hours' => (int) ($parts[0] ?? 0),
            'minutes' => (int) ($parts[1] ?? 0),
            'seconds' => (int) ($parts[2] ?? 0),
        ];
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

    /**
     * Returns 'null' if not solved or 'timestamp' if Challenge solved by User
     *
     * @param Challenge $challenge
     * @param User|null $user
     * @return null|string
     */
    public static function isChallengeSolved(Challenge $challenge, User|null $user = null): null|string
    {
        $user_id = $user ? $user->id : auth()->user()->id;
        $pivot = $challenge->users->where(fn ($q) => $q->id === $user_id)->first()->pivot ?? null;
        return $pivot ? $pivot->solved_at : null;
    }

    /**
     * Returns a collection of user solved challenges
     * with bonus XP and extra bonus
     *
     * @param User $user
     * @return Collection
     */
    public static function userSolvedChallenges(User $user): Collection
    {
        return $user->challenges()
            ->wherePivotNotNull('solved_at')
            ->select('challenges.id', 'challenges.title', 'bonus_xp', 'extra_bonus')
            ->orderBy('title', 'asc')
            ->get();
    }

    /**
     * Number of User solved challenges
     *
     * @param User $user
     * @return integer
     */
    public static function nbUserSolvedChallenges(User $user): int
    {
        return $user->loadCount(['challenges as solved_challenges_count' => function ($query) {
            $query->whereNotNull('solved_at');
        }])->solved_challenges_count;
    }

    /**
     * Metrics - User Performance
     * Solved challenges (paginated)
     *
     * @param User $user
     * @param integer $per_page
     * @param boolean $ordered
     * @param \Illuminate\Database\Query\Builder|null $builder
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function userSolvedChallengesMetrics(User $user, int $per_page = 3, bool $ordered = false, \Illuminate\Database\Query\Builder|null $builder = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        if (!$builder) $builder = self::userSolvedChallengesBuilder($user);
        
        $ordered 
            ? $builder->orderBy('challenges.title', 'asc')
            : $builder->orderBy('challenge_solver.solved_at', 'asc');
        
        return $builder->paginate($per_page);
    }

    /**
     * Metrics - User Performance
     * Solved challenges
     *
     * @param User $user
     * @param boolean $ordered
     * @return \Illuminate\Support\Collection
     */
    // public static function userSolvedChallengesMetrics(User $user, bool $ordered = false): \Illuminate\Support\Collection
    // {
    //     $builder = self::userSolvedChallengesBuilder($user);
    //     $ordered 
    //         ? $builder->orderBy('challenges.title', 'asc')
    //         : $builder->orderBy('challenge_solver.solved_at', 'asc');
        
    //     return $builder->get();
    // }

    /**
     * User solved challenges builder
     *
     * @param User $user
     * @return \Illuminate\Database\Query\Builder
     */
    public static function userSolvedChallengesBuilder(User $user): \Illuminate\Database\Query\Builder
    {
        return DB::table('challenge_solver')
            ->join('challenges', 'challenge_solver.challenge_id', '=', 'challenges.id')
            ->join('difficulties', 'challenges.difficulty_id', '=', 'difficulties.id')
            ->join('statuses', 'challenges.status_id', '=', 'statuses.id')
            ->join('challenge_topic', 'challenges.id', '=', 'challenge_topic.challenge_id')
            ->join('topics', 'challenge_topic.topic_id', '=', 'topics.id')
            ->leftJoin('challenge_language', 'challenges.id', '=', 'challenge_language.challenge_id')
            ->leftJoin('languages', 'challenge_language.language_id', '=', 'languages.id')
            ->whereNotNull('challenge_solver.solved_at')
            ->where('challenge_solver.user_id', '=', $user->id)
            ->select(
                'challenges.id',
                'challenges.title',
                'challenges.description',
                'challenges.challenge_slug',
                'topics.id as topic_id',
                'topics.name as topic_name',
                'difficulties.id as difficulty_id',
                'difficulties.name as difficulty_name',
                'statuses.id as status_id',
                'statuses.name as status_name',
                'languages.id as language_id',
                'languages.name as language_name',
                'challenges.time_limit',
                'challenge_solver.solved_at',
                'challenge_solver.solved_time_seconds',
                'challenge_solver.attempts',
                'challenge_solver.bonus_xp',
                'challenge_solver.extra_bonus',
                'challenge_solver.solution_code',
                DB::raw('challenge_solver.bonus_xp + challenge_solver.extra_bonus as total_bonus')
            );
    }

    /**
     * Seconds to 'H:i:s'
     *
     * @param integer $solved_time_seconds
     * @return string
     */
    public static function secondsToString(int $solved_time_seconds): string
    {
        return Carbon::createFromFormat('H:i:s', gmdate('H:i:s', $solved_time_seconds))->format('H:i:s');
    }

    /**
     * Returns an integer percentage of solved challenges
     *
     * @param integer $solved_challenges_count
     * @param integer $nb_challenges
     * @return integer
     */
    public static function percentageSolved(int $solved_challenges_count = 0, int $nb_challenges = 0): int
    {
        return $nb_challenges ? number_format(($solved_challenges_count * 100) / $nb_challenges, 0) : 0;
    }

    /**
     * Get available OpenAI API models from 'mrlibelula@gmail.com' account
     * This action cost OpenAI tokens
     *
     * @return array
     */
    public static function getOpenAIModelsCompletion(): array
    {
        return OpenAI::models()->list()->toArray()['data'];
    }

    /**
     * Get seconds from an array of hours, minutes and seconds
     *
     * @param array $time_array
     * @return integer
     */
    public static function calculateSeconds(array $time_array = [
        'hours' => 1,
        'minutes' => 0,
        'seconds' => 0,
    ]): int
    {
        $carbon = Carbon::create(2000, 1, 1, $time_array['hours'], $time_array['minutes'], $time_array['seconds']);
        return $carbon->diffInSeconds(Carbon::create(2000, 1, 1, 0, 0, 0));
    }

    /**
     * Calculates user completion time (in seconds)
     *
     * @param Challenge $challenge
     * @param array $elapsed_time
     * @return integer
     */
    public static function calculateCompletionTime(Challenge $challenge, array $elapsed_time = [
        'hours' => 0,
        'minutes' => 10,
        'seconds' => 0,
    ]): int
    {
        $completed_in = Tool::calculateSeconds($elapsed_time); // seconds
        $challenge_seconds = Carbon::createFromTimeString($challenge->time_limit)->diffInSeconds(Carbon::today()); // seconds
        return $challenge_seconds - $completed_in;
    }

    /**
     * Calculates the total User's challenge bonus XP and extra_bonus from DB
     *
     * @param integer $user_id
     * @param integer $challenge_id
     * @return array
     */
    public static function totalUserChallengeBonus(int $user_id, int $challenge_id): array
    {
        $db = DB::table('challenge_solver')
            ->select(DB::raw('SUM(bonus_xp) as total_bonus_xp, SUM(extra_bonus) as total_extra_bonus, SUM(bonus_xp) + SUM(extra_bonus) as total_bonus'))
            ->where('user_id', $user_id)
            ->where('challenge_id', $challenge_id)
            ->first();
        
        return [
            'total_bonus_xp' => $db->total_bonus_xp ?? 0, 
            'total_extra_bonus' => $db->total_extra_bonus ?? 0,
            'total_bonus' => $db->total_bonus ?? 0,
        ];
    }

    /**
     * TOTAL User bonus
     *
     * @param integer $user_id
     * @return integer
     */
    public static function totalUserBonus(int $user_id): int
    {
        return DB::table('challenge_solver')
            ->select(DB::raw('SUM(bonus_xp) + SUM(extra_bonus) as total_bonus'))
            ->where('user_id', $user_id)
            ->first()
            ->total_bonus ?? 0;
    }

    /**
     * Update User options on DB
     *
     * @param User $user
     * @param stdClass $updated_user_options_tree
     * @return bool
     */
    public static function updateUserOptions(User $user, stdClass $updated_user_options_tree): bool
    {
        $user->options = json_encode($updated_user_options_tree);
        return $user->save();
    }

    /**
     * Returns the feedback_history data structure
     *
     * @param integer $id
     * @param integer $nb_solved_challenges
     * @param string $prompt
     * @param string $ai_feedback
     * @param string|null $created_at
     * @return array
     */
    public static function feedbackHistoryDataStructure(int $id, int $nb_solved_challenges, string $prompt, string $ai_feedback, string|null $created_at = null): array
    {
        return [
            'id' => $id,
            'nb_solved_challenges' => $nb_solved_challenges,
            'enc_prompt' => self::encode($prompt),
            'ai_feedback' => $ai_feedback,
            'created_at' => isset($created_at) ? $created_at : date('Y-m-d H:i:s'),
        ];
    }

    /**
     * Append an A.I. feedback to the User options
     *
     * @param User $user
     * @param string $feedback_type
     * @param mixed $feedback
     * @return mixed
     */
    public static function addFeedback(User $user, string $feedback_type, mixed $feedback): mixed
    {
        $history_type = 'ai_' . $feedback_type . '_feedback_history';
        $user->appendToMetricsPerformanceFeedbackHistoryArray($history_type, $feedback);
        return $feedback;
    }

    /**
     * Get User feedback history.
     * Return all types if !$feedback_type
     *
     * @param User $user
     * @param string|null|null $feedback_type
     * @return Collection
     */
    public static function userFeedbackHistory(User $user, string|null $feedback_type = null): Collection
    {
        $performance = $user->options()->metrics->performance;
        if (!$feedback_type) {
            return collect($performance);  // returns all perfomance feedback types
        }

        switch ($feedback_type) {
            case 'problem_specific':
                return collect($performance->ai_problem_specific_feedback_history);
            
            case 'optimization':
                return collect($performance->ai_optimization_feedback_history);
            
            case 'best_practices':
                return collect($performance->ai_best_practices_feedback_history);
            
            default:
                return collect($performance);
        }
    }

    /**
     * Returns topic_id from Topic name
     *
     * @param string $topic_name
     * @return integer
     */
    public static function getTopicIdFromName(string $topic_name): int
    {
        return Topic::select('id')->where('name', 'like', '%' . $topic_name .'%')->first()->id;
    }

    /**
     * Get the size of a variable
     *
     * @param mixed $data
     * @return array
     */
    public static function getVariableSize(mixed $data): array
    {
        // Serialize the data
        $serializedData = serialize($data);
    
        // Get the size in bytes
        $sizeInBytes = strlen($serializedData);
    
        // Convert to KB and MB
        $sizeInKB = $sizeInBytes / 1024;
        $sizeInMB = $sizeInKB / 1024;
    
        return [
            'bytes' => $sizeInBytes,
            'kilobytes' => $sizeInKB,
            'megabytes' => $sizeInMB,
        ];
    }
}