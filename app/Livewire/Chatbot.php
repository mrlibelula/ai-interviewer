<?php

namespace App\Livewire;

use App\Models\Challenge;
use App\Tool;
use Exception;
use Livewire\Component;

class Chatbot extends Component
{
    public $challenge;
    public $chat_welcome;
    public $openai_chat_settings;

    public array $messages = [];
    public string $last_chatbot_message = '';
    public string $user_color = 'fuchsia';
    public string $user_emoji = '🐵';
    public string $chatbot_color = 'emerald';
    public string $chatbot_emoji = '🤖';

    public string $user_code;

    protected $listeners = [
        'appended-chat-message' => 'appendedChatMessage', 
        'userCode', 
        'saveUserCode', 
        'complexityCode'
    ];

    /**
     * For 'user code' persisting purposes
     *
     * @param string $code
     * @return void
     */
    public function saveUserCode(string $code)
    {
        $this->user_code = $code;
        try {
            auth()->user()->updateChallenge($this->challenge, [
                'solution_code' => $code,
            ]);
            Tool::toastr($this, 'success', [
                'message' => 'Code saved',
            ]);
        } catch (Exception $e) {
            Tool::toastr($this, 'error', [
                'message' => 'Could not save the code. ' . $e->getMessage(),
            ]);
        }
        
    }

    /**
     * Asks GPT to analyze the code in terms of time/space complexity
     *
     * @param string $code
     * @return void
     */
    public function complexityCode(string $code)
    {
        $this->user_code = $code;
        $this->saveUserCode($code);

        $this->messages[] = [
            'role' => 'user',
            'content' => 'auto-generated: "analyze the time/space complexity (big-O notation) of my code"',
        ];

        $blueprint = env('OPENAI_COMPLEXITY_ANALYSIS_USER_CODE_PROMPT_BASE_TEXT');
        $prompt = Tool::replaceWildcards($blueprint, collect([
            'user_code' => $code,
            'challenge' => $this->challenge->title . ' (' . $this->challenge->description . ')',
            'user_name' => auth()->user()->name ?? 'user',
        ]));

        $this->messages[] = [
            'role' => 'system',
            'content' => $prompt,
        ];

        $this->openai_chat_settings['messages'] = $this->messages;
        auth()->user()->updateChallenge($this->challenge, ['openai_chat_settings' => $this->openai_chat_settings]);

        $completion = Tool::getLLMCompletion($this->messages);

        if (!$completion instanceof \OpenAI\Responses\Chat\CreateResponse) {
            Tool::toastr($this, 'error', [
                'message' => $completion,
            ]);
            return;
        }

        $completion_role = $completion->choices[0]->message->role;
        $completion_content = $completion->choices[0]->message->content;

        array_push($this->messages, [
            'role' => $completion_role,
            'content' => $completion_content,
        ]);

        // save data
        $this->openai_chat_settings['messages'] = $this->messages;
        auth()->user()->updateChallenge($this->challenge, ['openai_chat_settings' => $this->openai_chat_settings]);

        $this->getLastChatMessage();
        $this->dispatch('speak');
    }

    /**
     * Asks GPT to analyze the user code and check if its solving the problem
     *
     * @param string $code
     * @return void
     */
    public function userCode(string $code)
    {
        $this->user_code = $code;
        $this->saveUserCode($code);

        $this->messages[] = [
            'role' => 'user',
            'content' => 'auto-generated: "analyze my solution code"',
        ];

        $blueprint = env('OPENAI_ANALYZE_USER_CODE_PROMPT_BASE_TEXT');
        $prompt = Tool::replaceWildcards($blueprint, collect([
            'user_code' => $code,
            'challenge' => $this->challenge->title . ' (' . $this->challenge->description . ')',
            'user_name' => auth()->user()->name ?? 'user',
        ]));
        
        $this->messages[] = [
            'role' => 'system',
            'content' => $prompt,
        ];

        $this->openai_chat_settings['messages'] = $this->messages;
        auth()->user()->updateChallenge($this->challenge, ['openai_chat_settings' => $this->openai_chat_settings]);

        $completion = Tool::getLLMCompletion($this->messages);

        if (!$completion instanceof \OpenAI\Responses\Chat\CreateResponse) {
            Tool::toastr($this, 'error', [
                'message' => $completion,
            ]);
            return;
        }
        
        $completion_role = $completion->choices[0]->message->role;
        $completion_content = $completion->choices[0]->message->content;

        $content_parts = explode('%%%%%', $completion_content);
        
        $content = trim($content_parts[0]) ?? '';
        $solved = filter_var(strtolower(trim($content_parts[1] ?? 'false')), FILTER_VALIDATE_BOOLEAN);

        info('Chatbot::userCode(string $code) at line 143');
        info([$this->challenge->title . ' (' . $this->challenge->id . ')' => ['user' => auth()->user()->email, 'solved' => $solved]]);
        if ($solved) $this->dispatch('challengeSolved');

        array_push($this->messages, [
            'role' => $completion_role,
            'content' => $content,
        ]);

        // save data
        $this->openai_chat_settings['messages'] = $this->messages;
        auth()->user()->updateChallenge($this->challenge, ['openai_chat_settings' => $this->openai_chat_settings]);

        $this->getLastChatMessage();
        $this->dispatch('speak');
    }

    public function appendedChatMessage(array $openai_chat_settings)
    {
        $this->messages = $openai_chat_settings['messages'];
        auth()->user()->updateChallenge($this->challenge, ['openai_chat_settings' => $openai_chat_settings]);

        $challenge = Challenge::select('id', 'title', 'description', 'difficulty_id')
            ->with(['topics:name', 'difficulty', 'languages:name'])
            ->whereId($this->challenge->id)
            ->first();

        // get OpenAI completion
        if (count($this->messages) === 1) {
            $blueprint = env('OPENAI_CHALLENGE_PROMPT_BASE_TEXT');

            $prompt = Tool::replaceWildcards($blueprint, collect([
                'challenge' => '(' . $challenge->title . '), ' . $challenge->description,
                'topic' => $challenge->topics->first()->name,
                'difficulty_level' => $challenge->difficulty->name,
                'language' => $challenge->languages->first()->name,
                'user' => auth()->user()->name,
            ]));

            array_unshift($this->messages, [
                'role' => 'system',
                'content' => $prompt,
            ]);
        }

        // Instructions to OpenAI API response. Use the phrase "Auto-generated message" before the main text
        $this->messages[] = [
            'role' => 'system',
            'content' => env('OPENAI_CHATBOT_RECOMMENDATIONS_TO_INTERVIEWER'),
        ];

        $completion = Tool::getLLMCompletion($this->messages);
        
        $completion_role = $completion->choices[0]->message->role;
        $completion_content = $completion->choices[0]->message->content;
        
        array_push($this->messages, [
            'role' => $completion_role,
            'content' => $completion_content,
        ]);

        // save data
        $this->openai_chat_settings['messages'] = $this->messages;
        auth()->user()->updateChallenge($this->challenge, ['openai_chat_settings' => $this->openai_chat_settings]);
        $this->getLastChatMessage();
        $this->dispatch('speak');
    }

    public function mount()
    {
        $this->messages = $this->openai_chat_settings['messages'] ?? [];
    }

    /**
     * 'listen' (speak) the last chat message
     *
     * @return void
     */
    public function getLastChatMessage()
    {
        $this->last_chatbot_message = count($this->messages) 
            ? end($this->messages)['content'] ?? ''
            : 'Hi ' . auth()->user()->name . ', ' . env('OPENAI_CHATBOT_WELCOME_MESSAGE');
    }

    public function render()
    {
        $this->getLastChatMessage();
        $this->dispatch('chatbot-loader-off');
        return view('livewire.chatbot');
    }
}
