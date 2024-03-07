<div x-data="{ isContextOpen: false }" class=" relative flex items-center gap-x-4 justify-between py-3 bg-white dark:bg-gray-900 border-b border-gray-200/70 dark:border-gray-700/50 w-full -ml-[4rem]">
    <!-- back button -->
    <button @click="window.history.back()" class="ml-[5rem] p-1">
        <svg class="h-7 w-7 text-gray-900 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-200 smooth-300 cursor-pointer" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"></path>
        </svg>
    </button>

    <div class=" text-lg font-semibold whitespace-nowrap px-2.5 rounded-full bg-gray-200/50 dark:bg-gray-600 text-gray-600 dark:text-gray-400">
        A.I. Interviewer
    </div>
    <div class=" hidden md:flex border-r border-gray-200 dark:border-gray-700">
        &nbsp;
    </div>
    <div class=" hidden md:flex whitespace-nowrap text-base opacity-60 font-semibold text-gray-900 dark:text-gray-200">
        Full-Stack Software Engineer
    </div>
    <div class=" hidden md:flex border-r border-gray-200 dark:border-gray-700">
        &nbsp;
    </div>
    <div class="w-full px-3">
        <input placeholder="Search challenges" class="form-input h-[2.25rem] w-full text-sm placeholder-gray-500 placeholder:text-sm " />
    </div>
    <div class=" hidden md:flex border-r border-gray-200 dark:border-gray-700">
        &nbsp;
    </div>
    <div class="flex items-center gap-x-4">
        <x-theme-switcher />
        <div @click="isContextOpen = !isContextOpen" class="text-gray-900 dark:text-gray-400 hover:text-gray-500 dark:hover:text-gray-200 smooth-300 cursor-pointer">
            <x-icon-ellipsis-vertical class="h-7 w-7" />
        </div>
    </div>

    <!-- options context menu -->
    <form method="POST" action="{{ route('logout') }}" x-data>
        @csrf
        <x-context x-show="isContextOpen" @click.away="isContextOpen = false">
    
            <x-context-item>
                <x-slot name="icon"><x-icon-progress /></x-slot>
                My progress
            </x-context-item>
    
            <x-context-item>
                <x-slot name="icon"><x-icon-chat /></x-slot>
                Interviews
            </x-context-item>

            <x-context-item>
                <x-slot name="icon"><x-icon-queue-list /></x-slot>
                Challenges
            </x-context-item>
            
            <x-context-item>
                <x-slot name="icon"><x-icon-list /></x-slot>
                Topics
            </x-context-item>

            <x-context-item>
                <x-slot name="icon"><x-icon-cog /></x-slot>
                Options
            </x-context-item>

            <!-- admin -->
            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('recruiter'))
            <x-context-divider />

            <x-context-item wire:navigate href="{{ route('admin-dashboard') }}">
                <x-slot name="icon"><x-icon-cog-admin /></x-slot>
                Administrative options
            </x-context-item>
            @endif
            
    
            <x-context-divider />
    
            <x-context-item @click.prevent="$root.submit();">
                <x-slot name="icon"><x-icon-logout /></x-slot>
                Sign out
            </x-context-item>
    
            
        </x-context>
    </form>
</div>
