<?php

namespace App\Livewire;

use App\Tool;
use Livewire\Component;
use App\Models\Challenge;
use Illuminate\Database\UniqueConstraintViolationException;

class Start extends Component
{
    public string $selected_difficulty;
    public int|null $selected_topic_id;
    public int|null $challenge_id;
    public $challenge = null;
    public string|null $challenge_slug;
    public array $challenge_ids = [];
    public bool $random = false;
    public bool $time_limit_end = false;
    public array $challenge_attributes = [];
    public string $chat_welcome = "I'm thrilled to have you here, ready to tackle some coding questions and challenges. Whether you're here to refine your coding skills, seek advice, or simply looking for a friendly coding companion, you're in the right place!.??Feel free to ask any questions, I'm eager to assist you and provide constructive feedback to help you grow as a coder. Let's dive into the world of algorithms and problem-solving together!.??Ready to embark on this coding adventure? Just type away, and let's get started!.";
    public int $bonus_xp = 0;
    public int $attempts = 0;
    public int $total_challenges_count = 0;
    public array $openai_chat_settings;

    protected $listeners = ['getChallenge', 'sendMessage', 'timeLimitEnded'];

    public function timeLimitEnded()
    {
        $this->time_limit_end = true;
        $this->bonus_xp = 0;
    }

    public function sendMessage(string $chat_message)
    {
        // append to '$openai_chat_settings->messages' array
        $attempted_challenge = auth()->user()->challenges->where('id', '=', $this->challenge->id)->first();
        if ($attempted_challenge) {
            $challenge_attributes = $attempted_challenge->pivot;
            $this->openai_chat_settings = json_decode($challenge_attributes->openai_chat_settings, true);
        }

        $this->openai_chat_settings['messages'][] = [
            'role' => 'user',
            'content' => $chat_message,
            // 'temperature' => 0.7,
            // 'max_tokens' => 64,
            // 'top_p' => 1
        ];
        
        $this->dispatch('appended-chat-message', $this->openai_chat_settings);
    }

    public function buildChatWelcomeMessage()
    {
        $text = 'Hi ' . auth()->user()->name . ', ' . $this->chat_welcome;
        $this->chat_welcome  = $text;
    }

    /**
     * Obtains the first available challange from the 'challenge_ids' list
     * and removes it from the list
     *
     * @return void
     */
    public function getChallenge()
    {
        $this->challenge = count($this->challenge_ids)
            ? Tool::fetchChallenge(array_shift($this->challenge_ids))
            : null;
        
        // set Challenge attributes for pivot table
        if ($this->challenge) {
            // verify if Challenge is already attached to a User (solver)
            $attempted_challenge = auth()->user()->challenges->where('id', '=', $this->challenge->id)->first();
            
            // increment attempts
            if ($attempted_challenge) {
                // already attached
                $challenge_attributes = $attempted_challenge->pivot;
                $this->attempts = $challenge_attributes->attempts + 1;
                $challenge_attributes->attempts = $this->attempts;
                $challenge_attributes->save();

                $this->challenge_attributes = $challenge_attributes->toArray();

                $this->openai_chat_settings = json_decode($challenge_attributes->openai_chat_settings, true);
                
            } else {
                // attach Challenge to current User (solver)
                $this->attempts++;
                try {
                    // dd($this->challenge->pivot);
                    // $challenge_attributes = $this->challenge->pivot;
                    // $this->openai_chat_settings = json_decode($challenge_attributes->openai_chat_settings, true);

                    $this->challenge_attributes = [
                        'current_time_limit' => $this->challenge->time_limit, 
                        'attempts' => $this->attempts, 
                        // 'bonus_xp' => $this->bonus_xp, 
                        // 'solution_code' => '', 
                        // 'openai_chat_settings' => json_encode($this->openai_chat_settings), 
                        // 'observations' => json_encode([]), 
                    ];
    
                    auth()->user()->attachChallenge($this->challenge, $this->challenge_attributes);
    
                } catch (UniqueConstraintViolationException $e) {
                    // challenge already attached to User, use current DB data instead
                    $this->challenge_attributes = auth()
                        ->user()
                        ->challenges
                        ->where('id', '=', $this->challenge->id)
                        ->first()
                        ->pivot
                        ->toArray();
                }
            }
            
        }
        
    }

    public function getChallenges()
    {
        if ($challenge = $this->challenge_id ? Tool::fetchChallenge($this->challenge_id, ['id'], []) : null) {
            $this->challenge_ids[] = $challenge->id;
        } else {
            $challenges = Challenge::byDifficultyAndTopic($this->selected_difficulty, $this->selected_topic_id, ['id'], false);
            $challenges->each(function ($challenge) {
                $this->challenge_ids[] = $challenge->id;
            });
        }
        if ($this->random) shuffle($this->challenge_ids);
        $this->total_challenges_count = Tool::challengesCount();
    }

    public function mount(string $enc_selected_difficulty, string $enc_selected_topic_id, string|null $enc_challenge_id = null, string|null $challenge_slug = null)
    {
        $this->buildChatWelcomeMessage();
        $this->selected_difficulty = Tool::decode($enc_selected_difficulty);
        $this->selected_topic_id = (int)Tool::decode($enc_selected_topic_id);
        $this->challenge_id = $enc_challenge_id ? Tool::decode($enc_challenge_id) : null;
        $this->challenge_slug = $challenge_slug;
        $this->getChallenges();
        $this->getChallenge();
        $this->removeSolutionCode();
    }

    public function removeSolutionCode()
    {
        if (isset($this->challenge->solution_code)) unset($this->challenge->solution_code);
    }

    public function render()
    {
        $this->removeSolutionCode();
        return view('livewire.start');
    }
}
