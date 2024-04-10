<?php

namespace App\Livewire;

use App\Models\Challenge;
use App\Tool;
use Livewire\Component;

class Chatbot extends Component
{
    public $challenge;
    public $chat_welcome;
    public $openai_chat_settings;

    public array $messages = [];
    public string $user_color = 'orange';
    public string $user_avatar = '🐵';
    public string $chatbot_color = 'sky';
    public string $chatbot_avatar = '🤖';

    protected $listeners = ['appended-chat-message' => 'appendedChatMessage'];

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

    }

    public function mount()
    {
        $this->messages = $this->openai_chat_settings['messages'] ?? [];
        // $this->user_color = $this->openai_chat_settings['color'] ?? 'orange';
        // $this->user_avatar = $this->openai_chat_settings['avatar'] ?? '🐵';
        // $this->chatbot_color = $this->openai_chat_settings['chatbot_color'] ?? 'sky';
        // $this->chatbot_avatar = $this->openai_chat_settings['chatbot_avatar'] ?? '🤖';
    }

    public function render()
    {
        return view('livewire.chatbot');
    }
}
