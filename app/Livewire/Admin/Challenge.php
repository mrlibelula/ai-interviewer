<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Challenge as DBChallenge;
use App\Models\Difficulty;
use App\Models\Framework;
use App\Models\Language;
use App\Models\Package;
use App\Models\Status;
use App\Models\Tag;
use App\Models\Topic;
use App\Models\Visibility;
use App\Tool;

class Challenge extends Component
{
    public string $current_route_name;
    public $challenges;
    public $topics;
    public $languages;
    public $packages;
    public $frameworks;
    public $difficulties;
    public $tags;
    public $statuses;
    public $visibilities;
    public int $hours = 0;
    public int $minutes = 0;
    public int $seconds = 0;
    public int $status_id;
    public int $difficulty_id;
    public int $visibility_id;
    
    public int $challenge_id = -1;
    public $challenge;
    public bool $challenge_changed = false;
    public string $search = '';

    /** title_asc|title_desc|newest|oldest */
    public string $sort = 'title_asc';

    /** Skip re-query when syncing the input label after a selection. */
    protected bool $syncingSearchLabel = false;

    protected $listeners = ['destroyChallenge', 'deleteChallenge'];

    // it's forceDeleting, but produces a bug (404) when selecting again another challenge from the challenges list
    // public function destroyChallenge()
    // {
    //     $this->challenge->forceDelete();
    //     $this->challenge = null;
    //     $this->getChallenges();
    //     $this->challenge_id = -1;
    // }

    public function deleteChallenge()
    {
        $this->challenge->delete();
        $this->challenge = null;
        $this->challenge_id = -1;
        $this->search = '';
        $this->getChallenges();
    }

    public function updatedDifficultyId()
    {
        $this->challenge_changed = true;
        $this->challenge->difficulty_id = $this->difficulty_id;
        $this->challenge->save();
    }

    public function updatedVisibilityId()
    {
        $this->challenge_changed = true;
        $this->challenge->visibility_id = $this->visibility_id;
        $this->challenge->save();
    }

    public function updatedStatusId()
    {
        $this->challenge_changed = true;
        $this->challenge->status_id = $this->status_id;
        $this->challenge->save();
    }

    public function updatedChallengeId()
    {
        $this->challenge_changed = false;
        if ($this->challenge_id !== -1) {
            $this->loadChallenge();
            // for setting up difficulty, status, visibility, time limit
            $this->difficulty_id = (int) $this->challenge->difficulty_id;
            $this->status_id = (int) $this->challenge->status_id;
            $this->visibility_id = (int) $this->challenge->visibility_id;

            $normalized = Tool::normalizeTimeLimit($this->challenge->time_limit);
            if ((string) $this->challenge->time_limit !== $normalized) {
                $this->challenge->time_limit = $normalized;
                $this->challenge->save();
            }

            $parts = Tool::timeLimitParts($normalized);
            $this->hours = $parts['hours'];
            $this->minutes = $parts['minutes'];
            $this->seconds = $parts['seconds'];
        } else {
            $this->challenge = null;
        }
    }

    public function updatedHours()
    {
        // validate
        if ($this->hours >= 0 && $this->hours <= 23) {
            $this->challenge->time_limit = sprintf('%02d:%02d:%02d', $this->hours, $this->minutes, $this->seconds);
            $this->challenge->save();
        }
    }

    public function updatedMinutes()
    {
        // validate
        if ($this->minutes >= 0 && $this->minutes <= 59) {
            $this->challenge->time_limit = sprintf('%02d:%02d:%02d', $this->hours, $this->minutes, $this->seconds);
            $this->challenge->save();
        }
    }

    public function updatedSeconds()
    {
        // validate
        if ($this->seconds >= 0 && $this->seconds <= 59) {
            $this->challenge->time_limit = sprintf('%02d:%02d:%02d', $this->hours, $this->minutes, $this->seconds);
            $this->challenge->save();
        }
    }

    public function getTopics()
    {
        $this->topics = Topic::select('id', 'name')->where('parent_id', '=', null)->orderBy('name', 'asc')->get();
    }

    public function getLanguages()
    {
        $this->languages = Language::select('id', 'name')->orderBy('name', 'asc')->get();
    }

    public function getPackages()
    {
        $this->packages = Package::select('id', 'name')->orderBy('name', 'asc')->get();
    }

    public function getFrameworks()
    {
        $this->frameworks = Framework::select('id', 'name')->orderBy('name', 'asc')->get();
    }

    public function getDifficulties()
    {
        $this->difficulties = Difficulty::select('id', 'name')->get();
    }

    public function getTags()
    {
        $this->tags = Tag::select('id', 'name')->orderBy('name', 'asc')->get();
    }

    public function getStatuses()
    {
        $this->statuses = Status::select('id', 'name')->get();
    }

    public function getVisibilities()
    {
        $this->visibilities = Visibility::select('id', 'name')->get();
    }

    public function updatedSearch(): void
    {
        if ($this->syncingSearchLabel) {
            return;
        }

        $this->getChallenges();
        $this->dispatch('challenge-picker-open');
    }

    public function selectChallenge(int $id): void
    {
        $this->challenge_id = $id;
        $this->updatedChallengeId();

        if ($this->challenge) {
            $this->syncingSearchLabel = true;
            $this->search = $this->challenge->title;
            $this->syncingSearchLabel = false;
        }
    }

    public function clearChallengeSearch(): void
    {
        $this->search = '';
        $this->getChallenges();
        $this->dispatch('challenge-picker-open');
    }

    public function updatedSort(): void
    {
        $this->getChallenges();
        $this->dispatch('challenge-picker-open');
    }

    public function getChallenges()
    {
        $query = DBChallenge::query()
            ->search($this->search)
            ->with('topics:id,name')
            ->select('id', 'title', 'banner_url', 'created_at');

        match ($this->sort) {
            'title_desc' => $query->orderBy('title', 'desc'),
            'newest' => $query->orderByDesc('created_at')->orderByDesc('id'),
            'oldest' => $query->orderBy('created_at')->orderBy('id'),
            default => $query->orderBy('title', 'asc'),
        };

        $this->challenges = $query->get();
    }

    public function loadChallenge()
    {
        $this->challenge = auth()->user()->hasRole('admin') || auth()->user()->hasRole('recruiter')
            ? Tool::fetchChallenge(challenge_id: $this->challenge_id, append_ai_solution: true)
            : Tool::fetchChallenge($this->challenge_id);
    }

    public function toggleTopic(Topic $topic)
    {
        $this->challenge_changed = true;
        $this->challenge->topics->contains($topic)
            ? $this->challenge->removeTopic($topic)
            : $this->challenge->addTopic($topic);

        $this->loadChallenge();
    }

    public function toggleLanguage(Language $language)
    {
        $this->challenge_changed = true;
        $this->challenge->languages->contains($language)
            ? $this->challenge->removeLanguage($language)
            : $this->challenge->addLanguage($language);

        $this->loadChallenge();
    }

    public function toggleFramework(Framework $fw)
    {
        $this->challenge_changed = true;
        $this->challenge->frameworks->contains($fw)
            ? $this->challenge->removeFramework($fw)
            : $this->challenge->addFramework($fw);

        $this->loadChallenge();
    }

    public function togglePackage(Package $package)
    {
        $this->challenge_changed = true;
        $this->challenge->packages->contains($package)
            ? $this->challenge->removePackage($package)
            : $this->challenge->addPackage($package);

        $this->loadChallenge();
    }

    public function toggleTag(Tag $tag)
    {
        $this->challenge_changed = true;
        $this->challenge->tags->contains($tag)
            ? $this->challenge->removeTag($tag)
            : $this->challenge->addTag($tag);

        $this->loadChallenge();
    }

    public function mount()
    {
        $this->current_route_name = request()->route()->getName();    // tackles livewire route name problem (livewire.update)
        $this->getChallenges();
        $this->getTopics();
        $this->getLanguages();
        $this->getDifficulties();
        $this->getFrameworks();
        $this->getPackages();
        $this->getTags();
        $this->getStatuses();
        $this->getVisibilities();
    }

    public function render()
    {
        return view('livewire.admin.challenge');
    }
}
