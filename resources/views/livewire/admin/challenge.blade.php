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
                    <div class="w-3/4 p-6 bg-white dark:bg-gray-700/50 shadow-md rounded-lg">

                        @livewire('challenge-card', ['challenge' => $challenge], key(uniqid()))

                    </div>
                    <!-- setup challenge panel -->
                    <div class="flex flex-col gap-y-6 p-4 w-1/4">
                        
                        <x-h6 class="w-full text-center">Setup Challenge</x-h6>
                        
                        <div>
                            <x-secondary-button :disabled="!$challenge_changed">Reset</x-secondary-button>
                        </div>

                        <!-- setup topics -->
                        <x-admin.setup-box :fixed="true">
                            <x-slot name="title">Topics</x-slot>
                            <div>
                                @foreach ($topics as $list_topic)
                                <x-admin.setup-box-list wire:click.prevent="toggleTopic({{ $list_topic }})">
                                    <input type="checkbox" {{ $challenge->topics->contains($list_topic) ? 'checked' : '' }} id="{{ $list_topic->id }}" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-slate-600 shadow-sm focus:ring-slate-500 dark:focus:ring-slate-600 dark:focus:ring-offset-gray-800" />
                                    <label for="{{ $list_topic->id }}" class="w-full whitespace-nowrap text-sm">
                                        {{ $list_topic->name }}
                                    </label>
                                </x-admin.setup-box-list>
                                @endforeach
                            </div>
                            <x-slot name="selected">
                                <span>Selected: </span>
                                @foreach ($challenge->topics as $topic)
                                <span><x-bold>{{ $topic->name }}{{ !$loop->last ? ', ' : '' }}</x-bold></span>
                                @endforeach
                            </x-slot>
                        </x-admin.setup-box>

                        <x-admin.setup-box>
                            <x-slot name="title">Difficulty</x-slot>
                            <div>
                                <select wire:model.live='difficulty_id' class="form-select w-full">
                                    @foreach ($difficulties as $difficulty)
                                    <option value="{{ $difficulty->id }}">
                                        {{ ucfirst($difficulty->name) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </x-admin.setup-box>

                        <div class="flex flex-col gap-y-1">
                            <x-h6>Difficulty</x-h6>
                            <select class="form-select w-full">
                                <option value="1">JavaScript</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-y-1">
                            <x-h6>Languages</x-h6>
                            <select class="form-select w-full">
                                <option value="1">JavaScript</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-y-1">
                            <x-h6>Frameworks</x-h6>
                            <select class="form-select w-full">
                                <option value="1">JavaScript</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-y-1">
                            <x-h6>Packages/Libraries</x-h6>
                            <select class="form-select w-full">
                                <option value="1">JavaScript</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-y-1">
                            <x-h6>Tags</x-h6>
                            <select class="form-select w-full">
                                <option value="1">JavaScript</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-y-1">
                            <x-h6>Time limit</x-h6>
                            <select class="form-select w-full">
                                <option value="1">JavaScript</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-y-1">
                            <x-h6>Status</x-h6>
                            <select class="form-select w-full">
                                <option value="1">JavaScript</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-y-1">
                            <x-h6>Visibility</x-h6>
                            <select class="form-select w-full">
                                <option value="1">JavaScript</option>
                            </select>
                        </div>

                        <div>
                            <x-secondary-button :disabled="!$challenge_changed">Reset</x-secondary-button>
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
