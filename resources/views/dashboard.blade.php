<x-app-layout>
    <x-slot name="header">
        <x-heading-content title="Dashboard" subtitle="Welcome to your A.I. interview training platform">
            <x-slot name="right">
                <div 
                    {{-- @click="$dispatch('askGPT')"  --}}
                    class="flex items-center gap-x-6"
                >
                    <x-button type="button">Start A.I. Interview</x-button>
                </div>
            </x-slot>
        </x-heading-content>
    </x-slot>

    <x-container>

        <livewire:welcome />
        
    </x-container>
</x-app-layout>
