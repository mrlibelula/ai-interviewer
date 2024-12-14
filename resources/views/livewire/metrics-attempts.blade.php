<div
    x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" 
    x-init="window.darkMode = localStorage.getItem('dark') === 'true';"
    @theme-changed.window="window.darkMode = !window.darkMode;"
>
    <x-heading>
        <x-heading-metrics>
            <x-slot:subtitle>A.I. Attempts Metrics</x-slot:subtitle>
        </x-heading-metrics>
    </x-heading>

    <x-container>
        @livewire('metrics-nav')
        
        <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Total Attempts Card -->
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6 dark:bg-gray-800">
                <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Total Attempts</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">
                    {{ number_format($total_attempts) }}
                </dd>
            </div>

            <!-- Average Attempts Card -->
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6 dark:bg-gray-800">
                <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Average Attempts per Challenge</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">
                    {{ number_format($average_attempts, 1) }}
                </dd>
            </div>
        </div>

        <!-- Detailed Attempts Table -->
        <div class="mt-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Challenge</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Difficulty</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Attempts</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Solved At</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Time Taken</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                        @foreach($solved_challenges as $challenge)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $challenge->title }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ ucfirst($challenge->difficulty_name) }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $challenge->attempts }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($challenge->solved_at)->format('M j, Y H:i') }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ \App\Tool::secondsToString($challenge->solved_time_seconds) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $solved_challenges->links() }}
            </div>
        </div>
    </x-container>
</div>