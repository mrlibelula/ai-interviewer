<?php

namespace App\Livewire\Admin;

use App\Models\Challenge;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Isolate;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Isolate]
class ImportedChallenges extends Component
{
    use WithPagination;
    use WithoutUrlPagination;

    public int $perPage = 5;

    public string $search = '';

    /** @var array<int, int> Challenge IDs imported during this page visit. */
    public array $newChallengeIds = [];

    /** Only mount nested challenge-card when expanded (safe mid-batch remorph). */
    public ?int $expandedChallengeId = null;

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
        $this->expandedChallengeId = null;
    }

    public function toggleExpanded(int $challengeId): void
    {
        $this->expandedChallengeId = $this->expandedChallengeId === $challengeId
            ? null
            : $challengeId;
    }

    #[On('ai-challenges-updated')]
    public function refreshImported(bool $resetPage = false, ?array $newChallengeIds = null): void
    {
        if (is_array($newChallengeIds)) {
            $this->newChallengeIds = array_values(array_map('intval', $newChallengeIds));
        }

        if ($resetPage) {
            $this->resetPage();
            $this->expandedChallengeId = null;
        }
    }

    protected function importedChallenges(): LengthAwarePaginator
    {
        return Challenge::query()
            ->with([
                'difficulty',
                'status',
                'visibility',
                'tags',
                'languages',
                'frameworks',
                'packages',
                'topics',
                'creators',
            ])
            ->whereNotNull('completion_id')
            ->search($this->search)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.admin.imported-challenges', [
            'importedChallenges' => $this->importedChallenges(),
        ]);
    }
}
