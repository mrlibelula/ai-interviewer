@props(['active' => false])
<button {{ $attributes->merge(['class' => 'p-1 rounded-md ' . ($active ? 'text-gray-900 dark:text-gray-300' : 'text-gray-400 dark:text-gray-600') . ' bg-gray-200 dark:bg-gray-800 hover:bg-gray-300 hover:dark:bg-gray-700 smooth-300 cursor-pointer']) }}>
    <x-icon-squares class="w-7 h-7" />
</button>