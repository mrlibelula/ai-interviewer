<div
    x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" 
    x-init="
        window.darkMode = localStorage.getItem('dark') === 'true';
    "
    @theme-changed.window="window.darkMode = !window.darkMode;"
>
    <x-heading>
        <x-heading-metrics>
            <x-slot:subtitle>Leaderboard</x-slot:subtitle>
        </x-heading-metrics>
    </x-heading>

    <x-container>
        @livewire('metrics-nav')
        
        <div class="mt-8">
            <!-- Filters -->
            <div class="flex justify-between mb-6">
                <div class="flex space-x-4">
                    <button 
                        wire:click="updateSort('xp')"
                        class="px-4 py-2 rounded-lg {{ $sortBy === 'xp' ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700' }}"
                    >
                        XP
                    </button>
                    <button 
                        wire:click="updateSort('completions')"
                        class="px-4 py-2 rounded-lg {{ $sortBy === 'completions' ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700' }}"
                    >
                        Completions
                    </button>
                    <button 
                        wire:click="updateSort('efficiency')"
                        class="px-4 py-2 rounded-lg {{ $sortBy === 'efficiency' ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700' }}"
                    >
                        Efficiency
                    </button>
                </div>
                <div class="flex space-x-4">
                    <button 
                        wire:click="updateTimeFrame('all')"
                        class="px-4 py-2 rounded-lg {{ $timeFrame === 'all' ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700' }}"
                    >
                        All Time
                    </button>
                    <button 
                        wire:click="updateTimeFrame('month')"
                        class="px-4 py-2 rounded-lg {{ $timeFrame === 'month' ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700' }}"
                    >
                        This Month
                    </button>
                    <button 
                        wire:click="updateTimeFrame('week')"
                        class="px-4 py-2 rounded-lg {{ $timeFrame === 'week' ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700' }}"
                    >
                        This Week
                    </button>
                </div>
            </div>

            <!-- Leaderboard Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">XP Earned</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Challenges Completed</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Efficiency Score</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($users as $index => $user)
                            <tr class="{{ $index % 2 === 0 ? 'bg-gray-50 dark:bg-gray-700/50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        #{{ $index + 1 }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $user->name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ number_format($user->total_xp ?? 0) }} XP
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $user->solved_count ?? 0 }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ number_format($user->solved_count ? ($user->total_xp / $user->solved_count) : 0, 1) }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-container>
</div>
