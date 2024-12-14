<div class="grid grid-cols-2 gap-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-7 2xl:grid-cols-8">
    <x-dashboard-item-emerald :active="request()->routeIs('metrics')" href="/metrics">
        Performance
    </x-dashboard-item-emerald>
    <x-dashboard-item-emerald :active="request()->routeIs('metrics-difficulty')" href="/metrics/difficulty">
        Difficulty
    </x-dashboard-item-emerald>
    <x-dashboard-item-emerald :active="request()->routeIs('metrics-hint-usage')" href="/metrics/hint-usage">
        Hint Usage
    </x-dashboard-item-emerald>
    <x-dashboard-item-emerald :active="request()->routeIs('metrics-topic')" href="/metrics/topic">
        Topic
    </x-dashboard-item-emerald>
    <x-dashboard-item-emerald :active="request()->routeIs('metrics-attempts')" href="/metrics/attempts">
        Attempts
    </x-dashboard-item-emerald>
    <x-dashboard-item-emerald :active="request()->routeIs('metrics-time-based')" href="/metrics/time-based">
        Time-based
    </x-dashboard-item-emerald>
    <x-dashboard-item-emerald :active="request()->routeIs('metrics-leaderboard')" href="/metrics/leaderboard">
        Leaderboard
    </x-dashboard-item-emerald>
    <x-dashboard-item-emerald :active="request()->routeIs('metrics-comparison')" href="/metrics/comparison">
        Comparison
    </x-dashboard-item-emerald>
</div>