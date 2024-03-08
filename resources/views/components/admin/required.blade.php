@props(['id', 'checked' => false, 'disabled' => true])
<div {{ $attributes->merge(['class' => 'flex items-center w-full lg:w-fit gap-x-[1.5rem] leading-tight px-2 lg:px-4 py-2 lg:py-3']) }}>
    <input {{ $checked ? 'checked' : '' }} {{ $disabled ? 'disabled' : '' }} type="checkbox" class="border-2 border-gray-300 bg-red-50 dark:border-gray-700 text-slate-600 shadow-sm focus:ring-slate-500 dark:focus:ring-slate-600 dark:focus:ring-offset-gray-800 form-checkbox rounded-full w-9 h-9 {{ $checked ? ' dark:border-green-400 dark:bg-green-600' : 'dark:bg-red-950/40 border-red-600 dark:border-red-500' }} smooth-300">
    <label for="{{ $id }}" class="flex flex-col ">
        <div class="font-semibold {{ $checked ? 'text-green-500 dark:text-gray-300/90' : 'text-red-700 dark:text-red-500' }}">
            {{ $slot }}
        </div>

        @isset($description)
        <div class="text-base leading-tight {{ $checked ? 'text-green-600 dark:text-gray-400/90' : '' }}">
            {{ $description }}
        </div>
        @endisset
    </label>
</div>