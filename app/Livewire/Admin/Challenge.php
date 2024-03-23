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
    public int $hours;
    public int $minutes;
    public int $seconds;
    public int $status_id;
    public int $difficulty_id;
    public int $visibility_id;
    
    public int $challenge_id;
    public $challenge;
    public $original_challenge; // a copy of the challenge for 'reset' purposes
    public bool $challenge_changed = false;

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
        $this->getChallenges();
        $this->challenge_id = -1;
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
            $this->difficulty_id = $this->challenge->difficulty_id;
            $this->status_id = $this->challenge->status_id;
            $this->visibility_id = $this->challenge->visibility_id;
            $parts = explode(':', $this->challenge->time_limit);
            $this->hours = $parts[0];
            $this->minutes = $parts[1];
            $this->seconds = $parts[2];
        } else {
            $this->challenge = null;
        }
    }

    public function updatedHours()
    {
        $this->challenge->time_limit = implode(':', [$this->hours, $this->minutes, $this->seconds]);
    }

    public function updatedMinutes()
    {
        $this->challenge->time_limit = implode(':', [$this->hours, $this->minutes, $this->seconds]);
    }

    public function updatedSeconds()
    {
        $this->challenge->time_limit = implode(':', [$this->hours, $this->minutes, $this->seconds]);
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

    public function getChallenges()
    {
        $this->challenges = DBChallenge::with('topics:id,name')->select('id', 'title')->orderBy('title', 'asc')->get();
    }

    public function loadChallenge()
    {
        $this->challenge = Tool::fetchChallenge($this->challenge_id);
        $this->original_challenge = $this->challenge;   // for 'reset' purposes
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
