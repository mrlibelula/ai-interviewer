<div 
    x-data="{ spinnerOn: false }"
    @spinner-off.window="spinnerOn = false"
    @spinner-on.window="spinnerOn = true"
    class="grid grid-cols-1 md:grid-cols-2 gap-10 py-10_"
>
    
    <!-- progress summary -->
    {{-- <x-dashboard-item title="Progress summary" :fixedHeight="false" x-data="{ isOpen: true }" class=" md:col-span-2"> --}}
        {{-- <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8"> --}}
        {{-- <x-dashboard-item class="flex items-center justify-around_ justify-start gap-x-[5rem]"> --}}
        
        {{-- <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3_ _xl:grid-cols-4 gap-10"> --}}
            
            {{-- <x-progress :number="$perc_solved">Success rate</x-progress> --}}

            {{-- <div class="flex flex-col gap-y-4 items-center gap-x-6">
                <x-progress-circle value="90" />
                <div>Sociability</div>
            </div>
            <div class="flex flex-col gap-y-4 items-center gap-x-6">
                <x-progress-circle value="80" />
                <div>Professionalism</div>
            </div>
            <div class="flex flex-col gap-y-4 items-center gap-x-6">
                <x-progress-circle value="45" />
                <div>Energy level</div>
            </div>
            <div class="flex flex-col gap-y-4 items-center gap-x-6">
                <x-progress-circle value="60" />
                <div>Communication skills</div>
            </div> --}}

            {{-- <x-progress :number="0">Performance by Topic</x-progress> --}}

            <!-- total xp points -->
            {{-- <div class="flex flex-col gap-y-4 items-center gap-x-6 text-center">
                <div class="flex items-center justify-center w-[8rem] h-[8rem] rounded-full shadow-lg border-[2px] border-gray-200 dark:border-gray-700" 
                    style="background: conic-gradient(#2fd399 100%, transparent 3%);"
                >
                    <div class="flex items-center justify-center whitespace-nowrap w-[6.3rem] h-[6.3rem] rounded-full bg-emerald-100/80 dark:bg-emerald-800/80 text-2xl text-gray-900 dark:text-gray-300 font-mono">
                        <span class=" mr-1">{{ $total_user_bonus_xp }}</span>{{ 'XP' }}
                    </div>
                </div>
                <div>Total <span class=" font-bold text-black dark:text-gray-200">XP</span> points</div>
            </div> --}}

        {{-- </div> --}}
    {{-- </x-dashboard-item> --}}

    <!-- AI models -->
    <x-dashboard-item title="{{ 'Available A.I. models (' . count($available_ai_models) . ')' }}" x-data="{ isOpen: true }">
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
    <x-dashboard-item title="Completed challenges {{ count($solved_challenges) ? '(' . count($solved_challenges) . ')' : '' }}" x-data="{ isOpen: true }">
        <ul>
            @foreach ($solved_challenges as $solved_challenge)
            <li>
                <x-dot />{{ $solved_challenge->title }}
            </li>
            @endforeach
        </ul>
    </x-dashboard-item>

    <!-- performance -->
    <x-dashboard-item title="Performance" x-data="{ isOpen: true }">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="flex flex-col gap-y-4 items-center">
                <x-progress-circle value="75" />
                <div>Success Rate</div>
            </div>
            <div class="flex flex-col gap-y-4 items-center">
                <x-progress-circle value="60" />
                <div>Completion Rate</div>
            </div>
            <div class="flex flex-col gap-y-4 items-center">
                <x-progress-circle value="85" />
                <div>Accuracy</div>
            </div>
            <div class="flex flex-col gap-y-4 items-center">
                <x-progress-circle value="40" />
                <div>Speed</div>
            </div>
        </div>
    </x-dashboard-item>

    <!-- achievements -->
    <x-dashboard-item title="Achievements & Badges" x-data="{ isOpen: true }">
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            <div class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <svg class="w-12 h-12 text-yellow-500 mb-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z"/>
                </svg>
                <span class="text-sm font-medium">First Challenge</span>
            </div>
            <!-- Add more badges as needed -->
        </div>
    </x-dashboard-item>

    <!-- recommended challenges -->
    <x-dashboard-item title="Recommended challenges" x-data="{ isOpen: true }">
        <ul class="space-y-3">
            <li class="flex items-center gap-x-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span>Advanced API Integration</span>
            </li>
            <li class="flex items-center gap-x-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span>Security Best Practices</span>
            </li>
        </ul>
    </x-dashboard-item>

    <!-- topics -->
    {{-- <x-dashboard-item title="Training topics" x-data="{ isOpen: true }">
        <ul>
        @foreach($topics as $topic)
            @livewire('tree-node', ['topic' => $topic, 'level' => 0], key($topic->id))
        @endforeach
        </ul>
    </x-dashboard-item> --}}

    <!-- learning resources -->
    <x-dashboard-item title="Learning resources" x-data="{ isOpen: true }">
        <div class="space-y-4">
            <a href="#" class="block p-4 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                <div class="font-medium mb-1">Documentation</div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Complete API documentation and guides</p>
            </a>
            <a href="#" class="block p-4 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                <div class="font-medium mb-1">Video Tutorials</div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Step-by-step video guides</p>
            </a>
        </div>
    </x-dashboard-item>

    <!-- community engagement -->
    <x-dashboard-item title="Community engagement" x-data="{ isOpen: true }">
        <div class="space-y-4">
            <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <div>
                    <div class="font-medium">Forum Activity</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">12 posts this month</p>
                </div>
                <x-progress-circle value="65" class="w-12 h-12" />
            </div>
            <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <div>
                    <div class="font-medium">Contributions</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">5 solutions shared</p>
                </div>
                <x-progress-circle value="40" class="w-12 h-12" />
            </div>
        </div>
    </x-dashboard-item>
    
</div>
