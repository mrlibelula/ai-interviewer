<div x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" 
    x-init="
        window.darkMode = localStorage.getItem('dark') === 'true';
    "
    @theme-changed.window="window.darkMode = !window.darkMode;"
>
    <x-heading>
        <x-heading-metrics>
            <x-slot:subtitle>A.I. Topic Usage Metrics</x-slot:subtitle>
        </x-heading-metrics>
    </x-heading>

    <x-container>

        @livewire('metrics-nav')

        @foreach ($topics as $topic)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">{{ $topic->name }}</h3>
        </div>
        @endforeach

    </x-container>
</div>