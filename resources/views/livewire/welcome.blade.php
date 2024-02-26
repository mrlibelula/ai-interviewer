<div class="grid grid-cols-1 md:grid-cols-2 gap-10 py-10">
    <!-- ai models -->
    <div class="flex flex-col gap-y-8 p-6 rounded-xl bg-gray-100 dark:bg-gray-800 shadow-md">
        <div class="flex items-center justify-between">
            <div class=" font-semibold text-gray-600 dark:text-gray-300">
                Available A.I. Models
            </div>
            <div>
                <x-icon-info class="w-6 h-6 text-gray-400 dark:text-gray-500" />
            </div>
        </div>
        <select class="form-select">
            @foreach (session('openai_available_models') ?? [] as $model)
            <option>
                {{ $model['id'] }}
            </option>
            @endforeach
        </select>
    </div>

    <!-- progress summary -->
    <div class="flex flex-col gap-y-8 p-6 rounded-xl bg-gray-100 dark:bg-gray-800 shadow-md">
        <div class="flex items-center justify-between">
            <div class=" font-semibold text-gray-600 dark:text-gray-300">
                My Progress Summary
            </div>
            <div>
                <x-icon-info class="w-6 h-6 text-gray-400 dark:text-gray-500" />
            </div>
        </div>
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            <div class="flex items-center gap-x-6">
                <template x-if="darkMode">
                    <x-progress-circle color="white" value="35" />
                </template>
                <template x-if="!darkMode">
                    <x-progress-circle color="black" value="35" />
                </template>
                <div>Sociability</div>
            </div>
            <div class="flex items-center gap-x-6">
                <x-progress-circle color="deeppink" value="80" />
                <div>Professionalism</div>
            </div>
            <div class="flex items-center gap-x-6">
                <x-progress-circle color="deeppink" value="45" />
                <div>Energy level</div>
            </div>
            <div class="flex items-center gap-x-6">
                <x-progress-circle color="deeppink" value="60" />
                <div>Communication skills</div>
            </div>
            
        </div>
        
    </div>

    <!-- ai video score detail -->
    <div class="flex flex-col gap-y-8 p-6 rounded-xl bg-gray-100 dark:bg-gray-800 shadow-md">
        <div class="flex items-center justify-between">
            <div class=" font-semibold text-gray-600 dark:text-gray-300">
                A.I. Video Score Detail
            </div>
            <div>
                <x-icon-info class="w-6 h-6 text-gray-400 dark:text-gray-500" />
            </div>
        </div>
    </div>

    <!-- topics -->
    <div class="flex flex-col gap-y-8 p-6 rounded-xl bg-gray-100 dark:bg-gray-800 shadow-md">
        <div class="flex items-center justify-between">
            <div class=" font-semibold text-gray-600 dark:text-gray-300">
                Main Topics
            </div>
            <div>
                <x-icon-info class="w-6 h-6 text-gray-400 dark:text-gray-500" />
            </div>
        </div>
        @foreach ($topics as $topic)
        <div class="flex items-start gap-x-1">
            <i class="caret down icon text-gray-400 dark:text-gray-600"></i>
            <div>
                {{ $topic->name }} <span class=" text-gray-400 dark:text-gray-500">({{ $topic->challenges_count }} challenges)</span>
                @if ($topic->children->count() > 0)
                    <x-topics.child :children="$topic->children" />
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
