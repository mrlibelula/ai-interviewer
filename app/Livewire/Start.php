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
    public int $bonux_xp = 0;

    protected $listeners = ['getChallenge', 'sendMessage', 'timeLimitEnded'];

    public function timeLimitEnded()
    {
        $this->time_limit_end = true;
        $this->bonux_xp = 0;
    }

    public function sendMessage($chat_message)
    {
        dd(trim($chat_message));
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

        // set challenge attributes for pivot table
        if ($this->challenge) {
            // attach challenge to current user
            try {
                $this->challenge_attributes = [
                    'current_time_limit' => $this->challenge->time_limit, 
                    'tries' => 0, 
                    'bonus_xp' => 0, 
                    'openai_chat_history' => json_encode([]), 
                    'observations' => json_encode([]),
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
