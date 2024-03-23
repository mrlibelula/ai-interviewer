<div>

    <template x-if="darkMode">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/github-dark.min.css" integrity="sha512-rO+olRTkcf304DQBxSWxln8JXCzTHlKnIdnMUwYvQa9/Jd4cQaNkItIUj6Z4nvW1dqK0SKXLbn9h4KwZTNtAyw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </template>
    <template x-if="!darkMode">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/default.min.css" integrity="sha512-hasIneQUHlh06VNBe7f6ZcHmeRTLIaQWFd43YriJ0UND19bvYRauxthDg8E4eVNPm9bRUhr5JGeqH7FRFXQu5g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </template>

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
                    <div class=" w-full xl:w-3/4 p-6 bg-white dark:bg-gray-700/50 shadow-md rounded-lg">
                        @livewire('challenge-card', ['challenge' => $challenge], key(uniqid()))
                    </div>
                    <!-- setup challenge panel -->
                    <div class="w-full xl:w-1/4 flex flex-col gap-y-6 p-4">
                        
                        <x-h6 class="w-full text-center">Setup Challenge</x-h6>

                        <!-- time_limit setup -->
                        <x-admin.setup-box>
                            <x-slot name="title">Time limit</x-slot>
                            <div class="flex items-center gap-x-1">
                                <x-input wire:model.live='hours' type="number" min="0" class="form-input w-full" />:
                                <x-input wire:model.live='minutes' type="number" min="0" class="form-input w-full" />:
                                <x-input wire:model.live='seconds' type="number" min="0" class="form-input w-full" />
                            </div>
                        </x-admin.setup-box>
                        
                        <!-- topics setup -->
                        <x-admin.setup-box :fixed="true">
                            <x-slot name="title">Topics</x-slot>
                            <div>
                                @foreach ($topics as $list_language)
                                <x-admin.setup-box-list wire:click.prevent="toggleTopic({{ $list_language }})">
                                    <input type="checkbox" {{ $challenge->topics->contains($list_language) ? 'checked' : '' }} id="{{ $list_language->id }}" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-slate-600 shadow-sm focus:ring-slate-500 dark:focus:ring-slate-600 dark:focus:ring-offset-gray-800" />
                                    <label for="{{ $list_language->id }}" class="w-full whitespace-nowrap text-sm">
                                        {{ $list_language->name }}
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

                        <!-- difficulty setup -->
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

                        <!-- languages setup -->
                        <x-admin.setup-box :fixed="true">
                            <x-slot name="title">Languages</x-slot>
                            <div>
                                @foreach ($languages as $list_language)
                                <x-admin.setup-box-list wire:click.prevent="toggleLanguage({{ $list_language }})">
                                    <input type="checkbox" {{ $challenge->languages->contains($list_language) ? 'checked' : '' }} id="{{ $list_language->id }}" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-slate-600 shadow-sm focus:ring-slate-500 dark:focus:ring-slate-600 dark:focus:ring-offset-gray-800" />
                                    <label for="{{ $list_language->id }}" class="w-full whitespace-nowrap text-sm">
                                        {{ $list_language->name }}
                                    </label>
                                </x-admin.setup-box-list>
                                @endforeach
                            </div>
                            <x-slot name="selected">
                                <span>Selected: </span>
                                @foreach ($challenge->languages as $language)
                                <span><x-bold>{{ $language->name }}{{ !$loop->last ? ', ' : '' }}</x-bold></span>
                                @endforeach
                            </x-slot>
                        </x-admin.setup-box>

                        <!-- frameworks setup -->
                        <x-admin.setup-box :fixed="true">
                            <x-slot name="title">Frameworks</x-slot>
                            <div>
                                @foreach ($frameworks as $list_fw)
                                <x-admin.setup-box-list wire:click.prevent="toggleFramework({{ $list_fw }})">
                                    <input type="checkbox" {{ $challenge->frameworks->contains($list_fw) ? 'checked' : '' }} id="{{ $list_fw->id }}" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-slate-600 shadow-sm focus:ring-slate-500 dark:focus:ring-slate-600 dark:focus:ring-offset-gray-800" />
                                    <label for="{{ $list_fw->id }}" class="w-full whitespace-nowrap text-sm">
                                        {{ $list_fw->name }}
                                    </label>
                                </x-admin.setup-box-list>
                                @endforeach
                            </div>
                            <x-slot name="selected">
                                <span>Selected: </span>
                                @foreach ($challenge->frameworks as $fw)
                                <span><x-bold>{{ $fw->name }}{{ !$loop->last ? ', ' : '' }}</x-bold></span>
                                @endforeach
                            </x-slot>
                        </x-admin.setup-box>

                        <!-- packages setup -->
                        <x-admin.setup-box :fixed="true">
                            <x-slot name="title">Packages</x-slot>
                            <div>
                                @foreach ($packages as $list_package)
                                <x-admin.setup-box-list wire:click.prevent="togglePackage({{ $list_package }})">
                                    <input type="checkbox" {{ $challenge->packages->contains($list_package) ? 'checked' : '' }} id="{{ $list_package->id }}" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-slate-600 shadow-sm focus:ring-slate-500 dark:focus:ring-slate-600 dark:focus:ring-offset-gray-800" />
                                    <label for="{{ $list_package->id }}" class="w-full whitespace-nowrap text-sm">
                                        {{ $list_package->name }}
                                    </label>
                                </x-admin.setup-box-list>
                                @endforeach
                            </div>
                            <x-slot name="selected">
                                <span>Selected: </span>
                                @foreach ($challenge->packages as $package)
                                <span><x-bold>{{ $package->name }}{{ !$loop->last ? ', ' : '' }}</x-bold></span>
                                @endforeach
                            </x-slot>
                        </x-admin.setup-box>

                        <!-- tags setup -->
                        <x-admin.setup-box :fixed="true">
                            <x-slot name="title">Tags</x-slot>
                            <div>
                                @foreach ($tags as $list_tag)
                                <x-admin.setup-box-list wire:click.prevent="toggleTag({{ $list_tag }})">
                                    <input type="checkbox" {{ $challenge->tags->contains($list_tag) ? 'checked' : '' }} id="{{ $list_tag->id }}" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-slate-600 shadow-sm focus:ring-slate-500 dark:focus:ring-slate-600 dark:focus:ring-offset-gray-800" />
                                    <label for="{{ $list_tag->id }}" class="w-full whitespace-nowrap text-sm">
                                        {{ $list_tag->name }}
                                    </label>
                                </x-admin.setup-box-list>
                                @endforeach
                            </div>
                            <x-slot name="selected">
                                <span>Selected: </span>
                                @foreach ($challenge->tags as $tag)
                                <span><x-bold>{{ $tag->name }}{{ !$loop->last ? ', ' : '' }}</x-bold></span>
                                @endforeach
                            </x-slot>
                        </x-admin.setup-box>

                        <!-- status setup -->
                        <x-admin.setup-box>
                            <x-slot name="title">Status</x-slot>
                            <div>
                                <select wire:model.live='status_id' class="form-select w-full">
                                    @foreach ($statuses as $status)
                                    <option value="{{ $status->id }}">
                                        {{ ucfirst($status->name) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </x-admin.setup-box>

                        <!-- visibility setup -->
                        <x-admin.setup-box>
                            <x-slot name="title">Visibility</x-slot>
                            <div>
                                <select wire:model.live='visibility_id' class="form-select w-full">
                                    @foreach ($visibilities as $visibility)
                                    <option value="{{ $visibility->id }}">
                                        {{ ucfirst($visibility->name) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </x-admin.setup-box>

                        <div>
                            <x-danger-button @click="$dispatch('deleteChallenge')" class="w-full">
                                <div class="w-full flex items-center justify-center gap-x-4">
                                    <div>
                                        <x-icon-trash class="w-6 h-6" />
                                    </div>
                                    Move to Trash
                                </div>
                            </x-danger-button>
                        </div>
                        <div>
                            <!-- <x-danger-button :disabled="!$challenge_changed">Reset</x-danger-button> -->
                            {{-- <x-danger-button @click="$dispatch('destroyChallenge')" class="w-full">Delete challenge</x-danger-button> --}}
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
