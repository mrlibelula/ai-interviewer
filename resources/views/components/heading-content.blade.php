@props(['title' => 'libe.dev', 'subtitle', 'top', 'right_vertical_position' => 'start'])
@php
    switch ($right_vertical_position) {
        case 'center':
            $r_position = 'md:items-center';
            break;

        case 'start':
            $r_position = 'md:items-start';
            break;
        
        case 'end':
            $r_position = 'md:items-end';
            break;
        
        default:
            $r_position = 'md:items-center';
            break;
    }
@endphp
<div {{ $attributes->merge(['class' => 'flex flex-col md:flex-row items-start ' . $r_position . ' gap-y-4 md:gap-x-8 py-12 px-4 sm:px-6 lg:px-8 -mt-2 xl:mt-6']) }}>
    <div class="flex flex-col text-center xl:text-left w-full">
        @isset($top)
        {{ $top }}
        @endisset
        <h2 class="text-3xl sm:text-4xl font-semibold text-gray-800 dark:text-gray-300 leading-tight">
            {{ $title }}
        </h2>
        @isset($subtitle)
        <h6 class="text-xl mt-2 text-gray-700 dark:text-gray-300/70">
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