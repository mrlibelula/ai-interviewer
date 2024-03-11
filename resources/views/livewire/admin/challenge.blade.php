<div>
    <x-admin.nav currentRoute="{{ $current_route_name }}" />
    
    <x-container>
        @if (count($challenges))
            {{-- <x-h5>Challenge</x-h5> --}}

            <select wire:model.live="challenge_id" class="form-select w-full">
                <option selected value="-1"> -- Challenge list -- </option>
                @foreach ($challenges as $db_challenge)
                <option value="{{ $db_challenge->id }}">
                    {{ $db_challenge->title }} [@foreach ($db_challenge->topics as $topic) {{ $topic->name }}@if(!$loop->last){{ ', ' }}@endif @endforeach]
                </option>
                @endforeach
            </select>

            @if ($challenge)
            <x-descr-list>
                <div class="p-6 bg-white dark:bg-gray-700/50 shadow-md rounded-lg">
                    @livewire('challenge-card', ['challenge' => $challenge], key($challenge->id))
                </div>
            </x-descr-list>
            @endif

        @else
            <x-not-found>
                <div class="flex flex-col justify-center items-center gap-y-4 w-full">
                    <div class="py-4">
                        No challenges on database
                    </div>
                    <a href="/admin/challenges/">
                        <x-button>Request Challenge to A.I.</x-button>
                    </a>
                </div>
            </x-not-found>
        @endif

    </x-container>
</div>
