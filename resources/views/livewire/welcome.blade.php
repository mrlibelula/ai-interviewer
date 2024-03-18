<div class="grid grid-cols-1 md:grid-cols-2 gap-10 py-10">
    
    <!-- progress summary -->
    <x-dashboard-item title="Progress summary" x-data="{ isOpen: true }" class=" md:col-span-2">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            <div class="flex items-center gap-x-6">
                <x-progress-circle value="90" />
                <div>Sociability</div>
            </div>
            <div class="flex items-center gap-x-6">
                <x-progress-circle value="80" />
                <div>Professionalism</div>
            </div>
            <div class="flex items-center gap-x-6">
                <x-progress-circle value="45" />
                <div>Energy level</div>
            </div>
            <div class="flex items-center gap-x-6">
                <x-progress-circle value="60" />
                <div>Communication skills</div>
            </div>
        </div>
    </x-dashboard-item>

    <!-- ai models -->
    <x-dashboard-item title="Available A.I. models">
        <select class="form-select">
            @foreach (session('openai_available_models') ?? [] as $model)
            <option>
                {{ $model['id'] }}
            </option>
            @endforeach
        </select>
    </x-dashboard-item>

    <!-- completed challenges -->
    <x-dashboard-item title="Completed challenges">
        
    </x-dashboard-item>

    <!-- performance -->
    <x-dashboard-item title="Performance">

    </x-dashboard-item>

    <!-- achievements -->
    <x-dashboard-item title="Achievements & Badges">

    </x-dashboard-item>

    <!-- recommended challenges -->
    <x-dashboard-item title="Recommended challenges">

    </x-dashboard-item>

    <!-- topics -->
    <x-dashboard-item title="Training topics">
        <ul>
        @foreach($topics as $topic)
            @livewire('tree-node', ['topic' => $topic, 'level' => 0], key($topic->id))
        @endforeach
        </ul>
    </x-dashboard-item>

    <!-- learning resources -->
    <x-dashboard-item title="Learning resources">

    </x-dashboard-item>

    <!-- community engagement -->
    <x-dashboard-item title="Community engagement">

    </x-dashboard-item>
    
</div>
