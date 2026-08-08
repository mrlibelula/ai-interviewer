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
     * Normalize pivot openai_chat_settings to array (cast or legacy JSON string).
     */
    protected function chatSettingsArray(mixed $settings): array
    {
        if (is_array($settings)) {
            return $settings;
        }
        if (is_string($settings) && $settings !== '') {
            return json_decode($settings, true) ?? ['messages' => []];
        }

        return ['messages' => []];
    }

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
            Tool::toastr($this, [
                'message' => 'Code saved',
            ], 'success');
        } catch (Exception $e) {
            Tool::toastr($this, [
                'message' => 'Could not save the code. ' . $e->getMessage(),
            ], 'error');
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

        $blueprint = Tool::promptTemplate('complexity_analysis');
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
            Tool::toastr($this, [
                'message' => $completion,
            ], 'error');
            $this->dispatch('chatbot-loader-off');
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
        $this->dispatch('chatbot-loader-off');
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

        $blueprint = Tool::promptTemplate('analyze_user_code');
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

        $completion = Tool::getLLMCompletion(
            $this->messages,
            Tool::jsonSchemaResponseFormat('code_analysis', Tool::codeAnalysisOutputSchema())
        );

        if (!$completion instanceof \OpenAI\Responses\Chat\CreateResponse) {
            Tool::toastr($this, [
                'message' => $completion,
            ], 'error');
            $this->dispatch('chatbot-loader-off');
            return;
        }
        
        $completion_role = $completion->choices[0]->message->role;
        $completion_content = $completion->choices[0]->message->content;

        $analysis = Tool::parseCodeAnalysisResponse((string) $completion_content);
        $content = $analysis['feedback'];
        $solved = $analysis['solved'];

        if ($solved) {
            $this->dispatch('challengeSolved');
        }

        array_push($this->messages, [
            'role' => $completion_role,
            'content' => $content,
        ]);

        // save data
        $this->openai_chat_settings['messages'] = $this->messages;
        auth()->user()->updateChallenge($this->challenge, ['openai_chat_settings' => $this->openai_chat_settings]);

        $this->getLastChatMessage();
        $this->dispatch('speak');
        $this->dispatch('chatbot-loader-off');
    }

    public function appendedChatMessage(array $openai_chat_settings)
    {
        $this->openai_chat_settings = $this->chatSettingsArray($openai_chat_settings);
        $this->messages = $this->openai_chat_settings['messages'] ?? [];
        auth()->user()->updateChallenge($this->challenge, ['openai_chat_settings' => $this->openai_chat_settings]);

        $challenge = Challenge::select('id', 'title', 'description', 'difficulty_id')
            ->with(['topics:name', 'difficulty', 'languages:name'])
            ->whereId($this->challenge->id)
            ->first();

        // get OpenAI completion
        if (count($this->messages) === 1) {
            $blueprint = Tool::promptTemplate('challenge_system');

            $prompt = Tool::replaceWildcards($blueprint, collect([
                'challenge' => '(' . $challenge->title . '), ' . $challenge->description,
                'topic' => $challenge->topics->first()->name ?? 'general',
                'difficulty_level' => $challenge->difficulty->name ?? 'medium',
                'language' => $challenge->languages->first()->name ?? 'javascript',
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
            'content' => Tool::promptTemplate('recommendations'),
        ];

        $completion = Tool::getLLMCompletion($this->messages);

        if (!$completion instanceof \OpenAI\Responses\Chat\CreateResponse) {
            Tool::toastr($this, [
                'message' => is_string($completion) ? $completion : 'Chat completion failed',
            ], 'error');
            $this->dispatch('chatbot-loader-off');
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
        $this->dispatch('chatbot-loader-off');
    }

    public function mount()
    {
        $this->openai_chat_settings = $this->chatSettingsArray($this->openai_chat_settings);
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
            : 'Hi ' . auth()->user()->name . ', ' . Tool::promptTemplate('welcome');
    }

    public function render()
    {
        $this->getLastChatMessage();

        return view('livewire.chatbot');
    }
}
