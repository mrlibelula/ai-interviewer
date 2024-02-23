<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-semibold text-gray-800 dark:text-gray-300 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <h6 class=" text-xl text-gray-700 dark:text-gray-300/70">
                {{ __('Welcome to A.I. Interviewer Platform') }}
            </h6>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/40 dark:bg-gray-800/80 overflow-hidden shadow-xl sm:rounded-lg">
                <x-welcome />
            </div>
        </div>
    </div>
</x-app-layout>
