<?php

namespace App\Livewire;

use App\Models\Challenge;
use Livewire\Component;

class TopHeader extends Component
{
    public string $query = '';

    /** @var array<int, array{id:int,title:string,challenge_slug:string,difficulty:?string,topic_id:?int,topic_name:?string}> */
    public array $searchResults = [];

    public bool $searchAttempted = false;

    public function updatedQuery(): void
    {
        $this->runSearch();
    }

    public function runSearch(): void
    {
        $term = trim($this->query);

        if ($term === '') {
            $this->searchResults = [];
            $this->searchAttempted = false;

            return;
        }

        $this->searchAttempted = true;
        $this->searchResults = Challenge::search($term)
            ->with(['difficulty', 'topics'])
            ->orderBy('title')
            ->limit(25)
            ->get()
            ->map(static function (Challenge $challenge): array {
                $topic = $challenge->topics->first();

                return [
                    'id' => (int) $challenge->id,
                    'title' => (string) $challenge->title,
                    'challenge_slug' => (string) $challenge->challenge_slug,
                    'difficulty' => $challenge->difficulty?->name,
                    'topic_id' => $topic?->id !== null ? (int) $topic->id : null,
                    'topic_name' => $topic?->name,
                ];
            })
            ->values()
            ->all();
    }

    public function clearSearch(): void
    {
        $this->query = '';
        $this->searchResults = [];
        $this->searchAttempted = false;
    }

    public function render()
    {
        return view('livewire.top-header');
    }
}
