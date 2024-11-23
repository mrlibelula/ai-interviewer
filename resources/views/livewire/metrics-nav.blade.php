<div class="grid grid-cols-2 gap-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-7 2xl:grid-cols-8">
    <x-dashboard-item-emerald :active="request()->routeIs('metrics')" href="/metrics">
        Performance
    </x-dashboard-item-emerald>
    <x-dashboard-item-emerald :active="request()->routeIs('metrics-difficulty')" href="/metrics/difficulty">
        Difficulty
    </x-dashboard-item-emerald>
    <x-dashboard-item-emerald :active="false" href="/">
        Hint Usage
    </x-dashboard-item-emerald>
    <x-dashboard-item-emerald :active="false" href="/">
        Topic
    </x-dashboard-item-emerald>
    <x-dashboard-item-emerald :active="false" href="/">
        Attempts
    </x-dashboard-item-emerald>
    <x-dashboard-item-emerald :active="false" href="/">
        Time-based
    </x-dashboard-item-emerald>
    <x-dashboard-item-emerald :active="false" href="/">
        Leaderboard
    </x-dashboard-item-emerald>
    <x-dashboard-item-emerald :active="false" href="/">
        Comparison
    </x-dashboard-item-emerald>
</div>