<div>
    <x-admin.nav currentRoute="{{ $current_route_name }}" />
    
    <x-container>
        @if (count($challenges))
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
                <div class="flex flex-col gap-y-6 xl:gap-y-0 xl:flex-row items-start gap-x-6">
                    <!-- challege card -->
                    <div class="w-full p-6 bg-white dark:bg-gray-700/50 shadow-md rounded-lg">
                        @livewire('challenge-card', ['challenge' => $challenge], key($challenge->id))
                    </div>
                    <!-- options panel -->
                    <div class="flex flex-col gap-y-6 p-4 w-full xl:w-[20rem]">
                        <x-h6 class="w-full text-center">Setup Challenge</x-h6>
                        <div class="flex flex-col gap-y-1">
                            <div>Topics</div>
                            <select class="form-select w-full">
                                <option value="1">JavaScript</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-y-1">
                            <div>Difficulty</div>
                            <select class="form-select w-full">
                                <option value="1">JavaScript</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-y-1">
                            <div>Languages</div>
                            <select class="form-select w-full">
                                <option value="1">JavaScript</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-y-1">
                            <div>Frameworks</div>
                            <select class="form-select w-full">
                                <option value="1">JavaScript</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-y-1">
                            <div>Packages/Libraries</div>
                            <select class="form-select w-full">
                                <option value="1">JavaScript</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-y-1">
                            <div>Tags</div>
                            <select class="form-select w-full">
                                <option value="1">JavaScript</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-y-1">
                            <div>Time limit</div>
                            <select class="form-select w-full">
                                <option value="1">JavaScript</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-y-1">
                            <div>Status</div>
                            <select class="form-select w-full">
                                <option value="1">JavaScript</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-y-1">
                            <div>Visibility</div>
                            <select class="form-select w-full">
                                <option value="1">JavaScript</option>
                            </select>
                        </div>
                    </div>
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
