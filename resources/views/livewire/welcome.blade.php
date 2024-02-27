<div class="grid grid-cols-1 md:grid-cols-2 gap-10 py-10">
    <!-- ai models -->
    <div x-data="{ isOpen: false }" class="flex flex-col gap-y-8 p-6 md:p-8 rounded-xl h-fit bg-gray-100 dark:bg-gray-800 shadow-md">
        <div class="flex items-center justify-between">
            <div class=" font-semibold text-gray-600 dark:text-gray-300 lg:text-2xl">
                Available A.I. Models
            </div>
            <div class="flex gap-x-4">
                <x-icon-info class="w-6 h-6 text-gray-400 dark:text-gray-500" />
                <i x-cloak x-show="isOpen" @click="isOpen = !isOpen"   class="caret down icon cursor-pointer"></i>
                <i x-cloak x-show="!isOpen" @click="isOpen = !isOpen" class="caret right icon cursor-pointer"></i>
            </div>
        </div>
        <div x-cloak x-show="isOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-75" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-75" class=" w-full">
            <select class="form-select">
                @foreach (session('openai_available_models') ?? [] as $model)
                <option>
                    {{ $model['id'] }}
                </option>
                @endforeach
            </select>

        </div>
    </div>

    <!-- progress summary -->
    <div x-data="{ isOpen: false }" class="flex flex-col gap-y-8 p-6 md:p-8 rounded-xl h-fit bg-gray-100 dark:bg-gray-800 shadow-md">
        <div class="flex items-center justify-between">
            <div class=" font-semibold text-gray-600 dark:text-gray-300 lg:text-2xl">
                My Progress Summary
            </div>
            <div class="flex gap-x-4">
                <x-icon-info class="w-6 h-6 text-gray-400 dark:text-gray-500" />
                <i x-cloak x-show="isOpen" @click="isOpen = !isOpen"   class="caret down icon cursor-pointer"></i>
                <i x-cloak x-show="!isOpen" @click="isOpen = !isOpen" class="caret right icon cursor-pointer"></i>
            </div>
        </div>
        <div x-cloak x-show="isOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-75" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-75" class="grid grid-cols-1 xl:grid-cols-2 gap-8">
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
    <div x-data="{ isOpen: false }" class="flex flex-col gap-y-8 p-6 md:p-8 rounded-xl h-fit bg-gray-100 dark:bg-gray-800 shadow-md">
        <div class="flex items-center justify-between">
            <div class=" font-semibold text-gray-600 dark:text-gray-300 lg:text-2xl">
                A.I. Video Score Detail
            </div>
            <div class="flex gap-x-4">
                <x-icon-info class="w-6 h-6 text-gray-400 dark:text-gray-500" />
                <i x-cloak x-show="isOpen" @click="isOpen = !isOpen"   class="caret down icon cursor-pointer"></i>
                <i x-cloak x-show="!isOpen" @click="isOpen = !isOpen" class="caret right icon cursor-pointer"></i>
            </div>
        </div>
        <div x-cloak x-show="isOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-75" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-75">
            &nbsp;
        </div>
    </div>

    <!-- topics -->
    <div x-data="{ isOpen: false }" class="flex flex-col gap-y-8 p-6 md:p-8 rounded-xl h-fit bg-gray-100 dark:bg-gray-800 shadow-md">
        <div class="flex items-center justify-between">
            <div class=" font-semibold text-gray-600 dark:text-gray-300 lg:text-2xl">
                Training Topics
            </div>
            <div class="flex gap-x-4">
                <x-icon-info class="w-6 h-6 text-gray-400 dark:text-gray-500" />
                <i x-cloak x-show="isOpen" @click="isOpen = !isOpen"   class="caret down icon cursor-pointer"></i>
                <i x-cloak x-show="!isOpen" @click="isOpen = !isOpen" class="caret right icon cursor-pointer"></i>
            </div>
        </div>

        <div x-cloak x-show="isOpen" class="-ml-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-75" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-75">
            <ul>
            @foreach($topics as $topic)
                @livewire('tree-node', ['topic' => $topic, 'level' => 0], key($topic->id))
            @endforeach
            </ul>
        </div>
        

    </div>
</div>
