<div>    
    <x-admin.nav currentRoute="{{ $currentRouteName }}" />

    <x-container class=" gap-y-12">

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-4 lg:gap-8">
            <x-counter nb="207">Challenges</x-counter>
            <x-counter nb="40+">Topics</x-counter>
            <x-counter nb="104">Active</x-counter>
            <x-counter nb="104">Inactive</x-counter>
            <x-counter nb="104">Archived</x-counter>
            <x-counter nb="29">Hard</x-counter>
            <x-counter nb="56">Medium</x-counter>
            <x-counter nb="83">Easy</x-counter>
            <x-counter nb="69">Public</x-counter>
            <x-counter nb="128">Private</x-counter>
            <x-counter nb="2">Creators</x-counter>
            <x-counter nb="4">Deleted</x-counter>
        </div>

    </x-container>
</div>
