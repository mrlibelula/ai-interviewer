<?php

namespace App\Livewire;

use App\Tool;
use Livewire\Component;

class Sidebar extends Component
{
    public array $feedbacks = [
        'problem_specific' => false,
        'optimization' => false,
        'best_practices' => false,
    ];

    protected $listeners = ['challengeSolved' => 'checkFeedbackNavNew'];

    public function checkFeedbackNavNew()
    {
        $user_options = auth()->user()->options();
        $feedbacks = $this->feedbacks;
        foreach ($feedbacks as $feedback_type => $bool) {
            $feedback_branch_str = 'ai_' . $feedback_type . '_feedback_history';
            $feedbacks = collect($user_options->metrics->performance->$feedback_branch_str);
            $last_feedback = $feedbacks->last();
            $feedback_nb_solved_challenges = isset($last_feedback->nb_solved_challenges) ? $last_feedback->nb_solved_challenges : -1;
            $db_nb_solved_challenges = Tool::nbUserSolvedChallenges(auth()->user());
            $this->feedbacks[$feedback_type] = $feedback_nb_solved_challenges === $db_nb_solved_challenges ? false : true;
        }
    }

    public function render()
    {
        $this->checkFeedbackNavNew();
        return view('livewire.sidebar');
    }
}
