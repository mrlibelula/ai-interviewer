<div>
    <x-slot name="header">
        <x-heading-content 
            title="Administrative options" 
            subtitle="LLM challenge management & analysis"
        >
            
        </x-heading-content>
    </x-slot>

    <x-container class="mb-12 gap-y-12">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-2">
            <x-admin.dashboard-item :active="$currentRoute === 'admin-dashboard'" href="/admin">
                Challenges dashboard
            </x-admin.dashboard-item>
            <x-admin.dashboard-item :active="$currentRoute === 'admin-prompt'" href="/admin/prompt">
                Build LLM Prompt
            </x-admin.dashboard-item>
            <x-admin.dashboard-item :active="$currentRoute === 'admin-challenges'" href="/admin/challenges">
                Import LLM Challenges
            </x-admin.dashboard-item>
            <x-admin.dashboard-item :active="$currentRoute === 'admin-challenge'" href="/admin/challenge">
                Challenge settings
            </x-admin.dashboard-item>
            <x-admin.dashboard-item :active="$currentRoute === 'admin-ai-settings'" href="/admin/ai-settings">
                AI admin settings
            </x-admin.dashboard-item>
        </div>
    </x-container>
</div>