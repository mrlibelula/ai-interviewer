<div 
    x-data="{ spinnerOn: false }"
    @spinner-off.window="spinnerOn = false"
    @spinner-on.window="spinnerOn = true"
    class="grid grid-cols-1 md:grid-cols-2 gap-10 py-10"
>
    
    <!-- progress summary -->
    <x-dashboard-item title="Progress summary" x-data="{ isOpen: true }" class=" md:col-span-2">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            <div class="flex items-center gap-x-6">
                <x-progress-circle value="{{ $perc_solved }}" />
                <div>Solved challenges</div>
            </div>
            <div class="flex items-center gap-x-6">
                <x-progress-circle value="90" />
                <div>Sociability</div>
            </div>
            <div class="flex items-center gap-x-6">
                <x-progress-circle value="80" />
                <div>Professionalism</div>
            </div>
            <div class="flex items-center gap-x-6">
                <x-progress-circle value="45" />
                <div>Energy level</div>
            </div>
            <div class="flex items-center gap-x-6">
                <x-progress-circle value="60" />
                <div>Communication skills</div>
            </div>
        </div>
    </x-dashboard-item>

    <!-- AI models -->
    <x-dashboard-item title="{{ 'Available A.I. models (' . count($available_ai_models) . ')' }}">
        <div class="flex flex-col gap-y-6 px-1 py-2">
            <div class="flex flex-col lg:flex-row items-center gap-y-6 lg:gap-x-6">
                <select class="form-select w-full">
                    @foreach ($available_ai_models as $model)
                    <option>{{ $model['id'] }}</option>
                    @endforeach
                </select>
                <div class=" w-full">
                    <x-secondary-button  class=" w-full flex justify-center text-sm"
                        wire:click="updateModelsList"
                        @click="$dispatch('spinner-on')" 
                    >
                        <div class="relative flex items-start">
                            <div :class="{ 'text-transparent': spinnerOn }">Update models list</div>
                            <div x-cloak x-show="spinnerOn" class=" absolute w-full flex justify-center">
                                <x-spinner class="w-6 h-6" />
                            </div>
                        </div>
                    </x-secondary-button>
                </div>
            </div>
            <div class="text-base text-center">
                Last updated: <span class=" text-gray-950 dark:text-gray-300">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', ($ai_models_last_updated ? $ai_models_last_updated : date('Y-m-d H:i:s')))->format('F jS, Y') }}</span>
            </div>
        </div>
    </x-dashboard-item>

    <!-- completed challenges -->
    <x-dashboard-item title="Completed challenges">
        <ul>
            @foreach ($solved_challenges as $solved_challenge)
            <li>
                <x-dot />{{ $solved_challenge->title }}
            </li>
            @endforeach
        </ul>
    </x-dashboard-item>

    <!-- performance -->
    <x-dashboard-item title="Performance">

    </x-dashboard-item>

    <!-- achievements -->
    <x-dashboard-item title="Achievements & Badges">

    </x-dashboard-item>

    <!-- recommended challenges -->
    <x-dashboard-item title="Recommended challenges">

    </x-dashboard-item>

    <!-- topics -->
    <x-dashboard-item title="Training topics">
        <ul>
        @foreach($topics as $topic)
            @livewire('tree-node', ['topic' => $topic, 'level' => 0], key($topic->id))
        @endforeach
        </ul>
    </x-dashboard-item>

    <!-- learning resources -->
    <x-dashboard-item title="Learning resources">

    </x-dashboard-item>

    <!-- community engagement -->
    <x-dashboard-item title="Community engagement">

    </x-dashboard-item>
    
</div>
