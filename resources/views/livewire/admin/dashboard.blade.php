<div>    
    <x-admin.nav currentRoute="{{ $current_route_name }}" />

    <x-container class=" gap-y-12">

        <x-descr-list>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-4 lg:gap-6">
                <x-counter nb="{{ $nb_challenges }}">Challenges</x-counter>
                <x-counter nb="{{ $nb_topics }}">Topics</x-counter>
                <x-counter nb="{{ $nb_active }}">Active</x-counter>
                <x-counter nb="{{ $nb_inactive }}">Inactive</x-counter>
                <x-counter nb="{{ $nb_archived }}">Archived</x-counter>
                <x-counter nb="{{ $nb_hard }}">Hard</x-counter>
                <x-counter nb="{{ $nb_medium }}">Medium</x-counter>
                <x-counter nb="{{ $nb_easy }}">Easy</x-counter>
                <x-counter nb="{{ $nb_public }}">Public</x-counter>
                <x-counter nb="{{ $nb_private }}">Private</x-counter>
                <x-counter nb="2">Creators</x-counter>
                <x-counter nb="0">Deleted</x-counter>
            </div>
        </x-descr-list>

    </x-container>
</div>
