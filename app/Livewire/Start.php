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
    public string $chat_welcome;
    public int $total_user_bonus_xp = 0;
    public int $total_user_extra_xp = 0;
    public int $total_bonus = 0;
    public int $total_xp = 0;
    public int $total_user_bonus = 0;
    public int $bonus_xp = 0;
    public int $extra_bonus = 0;
    public int $attempts = 0;
    public int $total_challenges_count = 0;
    public int $solved_challenges_count = 0;
    public array $openai_chat_settings;
    public bool $is_challenge_solved = false;
    public array $elapsed_time = [
        'hours' => 0,
        'minutes' => 0,
        'seconds' => 0,
    ];

    protected $listeners = ['getChallenge', 'sendMessage', 'timeLimitEnded', 'challengeSolved', 'currentElapsedTime'];

    public function nextChallenge()
    {
        $next_challenge = Tool::fetchChallenge(collect($this->challenge_ids)->first());
        return redirect()->route('interview-start', [
            'enc_selected_difficulty' => Tool::encode($this->selected_difficulty),
            'enc_selected_topic_id' => Tool::encode($this->selected_topic_id),
            'enc_challenge_id' => Tool::encode($next_challenge->id),
            'challenge_slug' => $next_challenge->challenge_slug ?? '',
        ]);
    }

    public function currentElapsedTime(int $hours, int $minutes, int $seconds)
    {
        $this->elapsed_time = [
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
        ];
    }

    public function challengeSolved()
    {
        if ($this->challenge) {
            // only give bonus xp to a User that didn't 'already solved' the Challenge
            $completion_time = 0;
            if (!$this->is_challenge_solved) {
                $bonus = Tool::calculateBonusXP(Tool::calculateCompletionTime($this->challenge, $this->elapsed_time));
                $this->bonus_xp = $bonus['bonus_xp'];
                $this->extra_bonus = $bonus['extra_bonus'];
                $completion_time = Tool::calculateCompletionTime($this->challenge, $this->elapsed_time);
                auth()->user()->updateChallenge($this->challenge, [
                    'bonus_xp' => $this->bonus_xp,
                    'extra_bonus' => $this->extra_bonus,
                    'solved_time_seconds' => $completion_time,
                    'solved_at' => date('Y-m-d H:i:s'),
                ]);
            }
            $this->getIsChallengeSolved();
            $this->totalUserBonus();
            $this->solvedChallengesCount();
            $this->dispatch('stop-timer');
        }
    }
    
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
        ];

        // append chat message
        $this->dispatch('appended-chat-message', $this->openai_chat_settings);
    }

    public function buildChatWelcomeMessage()
    {
        $text = 'Hi ' . auth()->user()->name . ', ' . env('OPENAI_CHATBOT_WELCOME_MESSAGE');
        $this->chat_welcome  = $text;
    }

    /**
     * Obtains the first available challange from the 'challenge_ids' list
     * and removes it from the list, or else, fetches the actual challenge_id challenge
     *
     * @return void
     */
    public function getChallenge()
    {
        $this->challenge = count($this->challenge_ids)
            ? Tool::fetchChallenge(array_shift($this->challenge_ids))
            : Tool::fetchChallenge($this->challenge_id);

        session()->put('challenge_ids', $this->challenge_ids);
        
        // set Challenge attributes for pivot table
        if ($this->challenge) {
            // verify if Challenge is already attached to a User (solver)
            $attempted_challenge = auth()->user()->challenges->where('id', '=', $this->challenge->id)->first();
            $this->getIsChallengeSolved();
            // increment attempts
            if ($attempted_challenge) {
                // already attached
                $challenge_attributes = $attempted_challenge->pivot;
                $this->attempts = !$this->is_challenge_solved ? $challenge_attributes->attempts + 1 : $challenge_attributes->attempts;
                $challenge_attributes->attempts = $this->attempts;
                $challenge_attributes->save();

                $this->challenge_attributes = $challenge_attributes->toArray();
                $this->openai_chat_settings = json_decode($challenge_attributes->openai_chat_settings, true);
                
            } else {
                // attach Challenge to current User (solver)
                $this->attempts++;
                try {
                    $this->challenge_attributes = [
                        'current_time_limit' => $this->challenge->time_limit, 
                        'attempts' => $this->attempts, 
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
        if (session()->has('challenge_ids')) {
            $this->challenge_ids = session()->get('challenge_ids');
        } else {
            if ($challenge = $this->challenge_id ? Tool::fetchChallenge($this->challenge_id, ['id'], []) : null) {
                $this->challenge_ids[] = $challenge->id;
            } else {
                $challenges = Challenge::byDifficultyAndTopic(
                    selected_difficulty: $this->selected_difficulty,
                    topic_id: $this->selected_topic_id,
                    user_id: auth()->user()->id,
                    return_cols: ['id'],
                    ordered: false
                );
                $challenges->each(fn ($challenge) => $this->challenge_ids[] = $challenge->id);
            }
            if ($this->random) shuffle($this->challenge_ids);
            session()->put('challenge_ids', $this->challenge_ids);
        }
    }

    public function getIsChallengeSolved()
    {
        $this->is_challenge_solved = Tool::isChallengeSolved($this->challenge) ? true : false;
    }

    public function totalChallengesCount()
    {
        $this->total_challenges_count = Tool::challengesCount();
    }

    public function solvedChallengesCount()
    {
        $this->solved_challenges_count = Tool::userSolvedChallenges(auth()->user())->count();
    }

    public function mount(string $enc_selected_difficulty, string $enc_selected_topic_id, string|null $enc_challenge_id = null, string|null $challenge_slug = null)
    {
        $this->is_challenge_solved = false;
        $this->buildChatWelcomeMessage();
        $this->selected_difficulty = Tool::decode($enc_selected_difficulty);
        $this->selected_topic_id = (int)Tool::decode($enc_selected_topic_id);
        $this->challenge_id = $enc_challenge_id ? Tool::decode($enc_challenge_id) : null;
        !$this->challenge_id ? session()->remove('challenge_ids') : '';
        $this->challenge_slug = $challenge_slug;
        $this->getChallenges();
        $this->totalChallengesCount();
        $this->solvedChallengesCount();
        $this->getChallenge();
        $this->totalUserBonus();
        $this->loadBonusXP();
    }

    public function loadBonusXP()
    {
        $this->bonus_xp = $this->challenge_attributes['bonus_xp'] ?? 0;
    }

    public function totalUserBonus()
    {
        if ($this->challenge) {
            $total_bonus = Tool::totalUserChallengeBonus(auth()->user()->id, $this->challenge->id);
            $this->total_user_bonus_xp = $total_bonus['total_bonus_xp'];
            $this->total_user_extra_xp = $total_bonus['total_extra_bonus'];
            $this->total_bonus = $total_bonus['total_bonus_xp'] + $total_bonus['total_extra_bonus'];
            $this->total_user_bonus = Tool::totalUserBonus(auth()->user()->id);
        }
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
