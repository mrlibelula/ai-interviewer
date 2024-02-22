<button x-cloak @click="darkMode = !darkMode" {{ $attributes->merge(['class' => 'cursor-pointer w-7 h-7 text-gray-900 dark:text-gray-400 hover:text-gray-500 dark:hover:text-gray-200 smooth-300']) }}>
    <div x-show="!darkMode">
        
        <x-icon-moon />

    </div>
    <div x-show="darkMode">
        
        <x-icon-sun />
        
    </div>
</button>