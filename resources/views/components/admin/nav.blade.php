<div>
    <x-slot name="header">
        <x-heading-content 
            title="Administrative options" 
            subtitle="LLM challenge management & analysis"
        >
            
        </x-heading-content>
    </x-slot>

    <x-container class="mb-12 gap-y-12">
        <div class="flex flex-col lg:flex-row items-center justify-between_ gap-2">
            <x-admin.dashboard-item :active="request()->routeIs('admin-dashboard')" href="/admin">
                Challenges dashboard
            </x-admin.dashboard-item>
            <x-admin.dashboard-item :active="request()->routeIs('admin-prompt')" href="/admin/prompt">
                Setup LLM prompt
            </x-admin.dashboard-item>
            <x-admin.dashboard-item :active="request()->routeIs('admin-challenges')">
                Obtain LLM challenges
            </x-admin.dashboard-item>
            <x-admin.dashboard-item :active="request()->routeIs('admin-challenge')">
                Challenge options
            </x-admin.dashboard-item>
            <x-admin.dashboard-item :active="request()->routeIs('admin-topics')">
                Manage topics
            </x-admin.dashboard-item>
        </div>
    </x-container>
</div>