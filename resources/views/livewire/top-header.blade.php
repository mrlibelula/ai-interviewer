<div x-data="{ isContextOpen: false }" class=" relative flex items-center gap-x-4 justify-between py-3 bg-white dark:bg-gray-900 border-b border-gray-200/70 dark:border-gray-700/50 w-full sm:-ml-[4rem]">
    <!-- hamburger -->
    <div class="sm:hidden ml-[1rem]">
        <svg class="h-7 w-7 text-gray-900 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-200 smooth-300 cursor-pointer" class="text-gray-200" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"></path>
        </svg>
    </div>
    
    <!-- back button -->
    <button @click="window.history.back()" class="sm:ml-[5rem] p-1">
        <svg class="h-7 w-7 text-gray-900 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-200 smooth-300 cursor-pointer" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"></path>
        </svg>
    </button>

    <a wire:navigate href="{{ route('interview') }}" class=" text-lg font-semibold whitespace-nowrap px-2.5 rounded-full bg-gray-200/50 dark:bg-gray-600 text-gray-600 dark:text-gray-400">
        A.I. Interviewer
    </a>
    <div class=" hidden md:flex border-r border-gray-200 dark:border-gray-700">
        &nbsp;
    </div>
    <div class=" hidden md:flex whitespace-nowrap text-base opacity-60 font-semibold text-gray-900 dark:text-gray-200">
        Full-Stack Software Engineer
    </div>
    <div class=" hidden md:flex border-r border-gray-200 dark:border-gray-700">
        &nbsp;
    </div>
    <div class="hidden sm:block w-full px-3">
        <input wire:model="query" wire:change="search" placeholder="Search challenges" class="form-input dark:bg-gray-800 h-[2.25rem] w-[70%] text-sm placeholder-gray-500 placeholder:text-sm " />
    </div>
    <!-- search results -->
    @if (count($searchResults))
    <div @click.away="$wire.clearSearch()" class="absolute z-40 top-[3.7rem] left-0 sm:left-20 w-full sm:w-[94%] bg-gray-100 dark:bg-gray-800 rounded-lg shadow-lg border-2 border-gray-200 dark:border-gray-700 h-[11.5rem] overflow-y-auto overflow-hidden">
        @foreach ($searchResults as $challenge)
        <a wire:navigate href="{{ route('interview-start', [
            'enc_selected_difficulty' => \App\Tool::encode($challenge->difficulty->name),
            'enc_selected_topic_id' => \App\Tool::encode($challenge->topics->first()->id),
            'enc_challenge_id' => \App\Tool::encode($challenge->id),
            'challenge_slug' => $challenge->challenge_slug,
        ]) }}" class="group">
            <div class="p-4 flex items-center justify-between group-hover:bg-gray-200 dark:group-hover:bg-gray-700 smooth-300 cursor-pointer">
                <div>
                    <span class="font-mono font-semibold px-2 text-lg bg-gray-200 dark:bg-gray-700 rounded-xl">Challenge</span> <span class="px-4">{{ $challenge->title }}</span>
                </div>
                <div class="font-mono font-semibold px-2 text-lg bg-gray-200 dark:bg-gray-700 rounded-xl">{{ $challenge->topics->first()->name }}</div>
            </div>
        </a>
        @endforeach
    </div>
    @endif

    <div class="hidden md:flex border-r border-gray-200 dark:border-gray-700">
        &nbsp;
    </div>
    <div class="flex justify-end items-center gap-x-4 w-full sm:w-fit">
        <x-theme-switcher />
        <button @click="isContextOpen = !isContextOpen" class="text-gray-900 dark:text-gray-400 hover:text-gray-500 dark:hover:text-gray-200 smooth-300 cursor-pointer">
            <x-icon-ellipsis-vertical class="h-7 w-7" />
        </button>
    </div>

    <!-- options context menu -->
    <form method="POST" action="{{ route('logout') }}" x-data>
        @csrf
        <x-context x-show="isContextOpen" @click.away="isContextOpen = false">
    
            <x-context-item class="md:hidden bg-gray-300 dark:bg-gray-500">
                Full-Stack Software Engineer
            </x-context-item>
            <x-context-item class="sm:hidden">
                <input placeholder="Search challenges" class="form-input h-[2.25rem] w-full text-sm placeholder-gray-500 placeholder:text-sm " />
            </x-context-item>
            <x-context-item wire:navigate href="{{ route('dashboard') }}">
                <x-slot name="icon"><x-icon-squares /></x-slot>
                My progress
            </x-context-item>

            <x-context-item wire:navigate href="{{ route('metrics') }}">
                <x-slot name="icon"><x-icon-progress /></x-slot>
                Metrics
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

            {{-- <x-context-item>
                <x-slot name="icon"><x-icon-cog /></x-slot>
                Options
            </x-context-item> --}}

            <!-- admin -->
            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('recruiter'))
            <x-context-divider />

            <x-context-item wire:navigate href="{{ route('admin-dashboard') }}">
                <x-slot name="icon"><x-icon-cog /></x-slot>
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
