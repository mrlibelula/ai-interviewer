@props(['title' => 'libe.dev', 'subtitle', 'top'])
<div {{ $attributes->merge(['class' => 'flex flex-col md:flex-row items-start md:items-end gap-y-4 md:gap-x-8 py-12']) }}>
    <div class="flex flex-col text-center xl:text-left w-full">
        @isset($top)
        {{ $top }}
        @endisset
        <h2 class="text-3xl sm:text-4xl font-semibold text-gray-800 dark:text-gray-300 leading-tight">
            {{ $title }}
        </h2>
        @isset($subtitle)
        <h6 class=" text-xl text-gray-700 dark:text-gray-300/70">
            {{ $subtitle }}
        </h6>
        @endisset
    </div>
    @isset($right)
    <div class="mx-auto xl:mx-0">
        {{ $right }}
    </div>
    @endisset
</div>