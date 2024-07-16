<x-app-layout>
    <x-slot name="header">
        <x-heading-content title="Dashboard" subtitle="Welcome to your A.I. interview training platform">
            <x-slot name="right">
                <a wire:navigate href="{{ route('interview') }}" class="flex items-center gap-x-6">
                    <x-button class="text-sm" type="button">Start A.I. Interview</x-button>
                </a>
            </x-slot>
        </x-heading-content>
    </x-slot>

    <x-container>

        <livewire:welcome />
        
    </x-container>
</x-app-layout>
