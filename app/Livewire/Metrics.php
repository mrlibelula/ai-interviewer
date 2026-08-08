<?php

namespace App\Livewire;

use App\Tool;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;
use stdClass;

class Metrics extends Component
{
    use WithPagination;
    
    protected \Illuminate\Database\Query\Builder $user_solved_challenges_builder;
    protected \Illuminate\Contracts\Pagination\LengthAwarePaginator $user_solved_challenges;
    protected Collection $user_solved_challenges_all;
    public int $challenges_count = 0;
    public int|string $perPage = 3;
    public int $success_rate = 0;
    public int $total_user_bonus_xp = 0;
    public string $ai_feedback = 'You must solve at least one challenge in order to get some A.I. feedback.';
    public array $feedback_nav = [
        'problem_specific' => true,
        'optimization' => false,
        'best_practices' => false,
    ];
    public array $new_feedback = [
        'problem_specific' => false,
        'optimization' => false,
        'best_practices' => false,
    ];
    public string $feedback_type = 'problem_specific';

    protected $listeners = ['toggled-feedback-nav' => 'toggleFeedbackType'];

    public function toggleFeedbackType(string $feedback_type)
    {
        $this->feedback_type = $feedback_type ?? 'problem_specific';
    }

    /**
     * Set A.I. feedback text by feedback type
     *
     * @return string
     */
    public function text(): string
    {
        if ($this->feedback_nav['problem_specific']) {
            $this->ai_feedback = 'problem_specific';
        } else if ($this->feedback_nav['optimization']) {
            $this->ai_feedback = 'optimization';
        } else if ($this->feedback_nav['best_practices']) {
            $this->ai_feedback = 'best_practices';
        }
        return $this->ai_feedback;
    }

    public function toggleFeedbackNav(string $feedback_type = 'problem_specific')
    {
        foreach ($this->feedback_nav as $type => $bool) {
            $this->feedback_nav[$type] = $type === $feedback_type ? true : false;
        }
    }

    public function aiFeedback()
    {
        $feedback_type = $this->feedback_type;
        // verify if there's been some new 'solved_challenges' (via nb_solved_challenges)
        // OR if 'metrics.performance.ai_feedback' options is empty
        // achieve this by checking if 'ai_feedback' is empty or if 'prompt' is different than .env generated prompt

        $this->ai_feedback = 'n/a';

        if ($this->user_solved_challenges->total()) {
            // generate prompt
            $solved_challenges_with_solver_code = '';
            $solved_challenges = $this->user_solved_challenges_all;
            $nb = 1;
            $solved_challenges->each(function ($solved_challenge) use (&$nb, &$solved_challenges_with_solver_code) {
                $solved_challenges_with_solver_code .= "\n"
                    . $nb++ . ') `'
                    . $solved_challenge->title
                    . '`, challenge description: `'
                    . $solved_challenge->description
                    . '`, with user solution code: ```'
                    . $solved_challenge->solution_code
                    . '```%%%'; // solved challenge separator (according to blueprint prompt)
            });
    
            // custom 'feedback type' text for base prompt
            $feedback_type_str = '';
            if ($feedback_type === 'problem_specific') {
                $feedback_type_str = 'Problem Specific';
            } else if ($feedback_type === 'optimization') {
                $feedback_type_str = 'Optimization';
            } else if ($feedback_type === 'best_practices') {
                $feedback_type_str = 'Code Style and Best Practices';
            }

            $wildcards = collect([
                'user_name' => auth()->user()->name,
                'solved_challenges_with_solver_code' => $solved_challenges_with_solver_code,
                'feedback_type' => $feedback_type_str,
            ]);
    
            $blueprint_problem_specific = Tool::promptTemplate('feedback');
            $blueprint_problem_specific = Tool::replaceWildcards($blueprint_problem_specific, $wildcards);
            
            // verify if its not the same prompt with .env blueprint to determine connection to openai api
            $connect_to_openai = false;
            $last_feedback = new stdClass;
            $history = Tool::userFeedbackHistory(auth()->user(), $feedback_type);
            $last_feedback = $history->last();
            if ($history->isEmpty()) {
                $connect_to_openai = true;
            } else {
                // Verify if prompts are the same
                if ($last_feedback->enc_prompt !== Tool::encode($blueprint_problem_specific)) {
                    $connect_to_openai = true;
                }
            }

            if ($connect_to_openai) {
                // prompt chatgpt
                $messages[] = [
                    'role' => 'system',
                    'content' => $blueprint_problem_specific,
                ];
                $completion = Tool::getLLMCompletion($messages);
                $ai_feedback = $completion->choices[0]->message->content;
                $branch = 'ai_' . $feedback_type . '_feedback_history';
                $feedback = Tool::addFeedback(auth()->user(), $feedback_type, Tool::feedbackHistoryDataStructure(auth()->user()->getNextFeedbackId($branch), $this->user_solved_challenges_all->count(), $blueprint_problem_specific, $ai_feedback));
                $this->ai_feedback = $feedback['ai_feedback'] ?? 'n/a';
                // $this->new_feedback[$feedback_type] = true;
                return;
            }

            $this->ai_feedback = $last_feedback->ai_feedback ?? 'n/a';

        } else {
            // no solved challenges found
        }
    }

    public function totalUserBonusXP()
    {
        $this->total_user_bonus_xp ? '' : $this->total_user_bonus_xp = Tool::totalUserBonus(auth()->user()->id);
    }

    public function userSolvedChallenges()
    {
        $this->user_solved_challenges_builder = Tool::userSolvedChallengesBuilder(auth()->user());
        $this->user_solved_challenges_all = $this->user_solved_challenges_builder->get();
        $this->user_solved_challenges = Tool::userSolvedChallengesMetrics(auth()->user(), $this->perPage, false, $this->user_solved_challenges_builder);
    }

    public function successRate()
    {
        $this->challenges_count ? '' : $this->challenges_count = Tool::challengesCount();
        $this->success_rate = Tool::percentageSolved($this->user_solved_challenges->total(), $this->challenges_count);
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function checkFeedbackNavNew()
    {
        $user_options = auth()->user()->options();
        $feedback_nav = $this->feedback_nav;
        foreach ($feedback_nav as $feedback_type => $bool) {
            $feedback_branch_str = 'ai_' . $feedback_type . '_feedback_history';
            $feedbacks = collect($user_options->metrics->performance->$feedback_branch_str);
            $last_feedback = $feedbacks->last();
            $feedback_nb_solved_challenges = isset($last_feedback->nb_solved_challenges) 
                ? $last_feedback->nb_solved_challenges 
                : -1;
            $db_nb_solved_challenges = $this->user_solved_challenges_all->count();
            $this->new_feedback[$feedback_type] = $feedback_nb_solved_challenges === $db_nb_solved_challenges 
                ? false 
                : true;
        }
    }

    public function render()
    {
        $this->userSolvedChallenges();
        $this->successRate();
        $this->totalUserBonusXP();
        $solved_challenges = $this->user_solved_challenges;
        
        $this->checkFeedbackNavNew();
        $this->aiFeedback();

        $this->dispatch('feedback-loader-off');

        return view('livewire.metrics', compact(['solved_challenges']));
    }
}
