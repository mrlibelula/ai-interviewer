@props(['title' => 'libe.dev', 'subtitle'])
<div class="flex flex-col md:flex-row items-start md:items-end gap-y-4 md:gap-x-8">
    <div class="flex flex-col text-center xl:text-left w-full">
        <h2 class="text-3xl sm:text-4xl font-semibold text-gray-800 dark:text-gray-300 leading-tight">
            {{ __($title) }}
        </h2>
        @isset($subtitle)
        <h6 class=" text-xl text-gray-700 dark:text-gray-300/70">
            {{ __($subtitle) }}
        </h6>
        @endisset
    </div>
    @isset($right)
    <div class="mx-auto xl:mx-0">
        {{ $right }}
    </div>
    @endisset
</div>