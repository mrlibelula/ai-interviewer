<x-app-layout>
    <x-slot name="header">
        <x-heading-content title="Dashboard" subtitle="Welcome to the A.I. Interview Training Platform">
            <x-slot name="right">
                <div class="flex items-center gap-x-6">
                    <x-button type="button">Start A.I. Interview Preparation</x-button>
                </div>
            </x-slot>
        </x-heading-content>
    </x-slot>

    <x-container>

        <livewire:welcome />
        
    </x-container>
</x-app-layout>
