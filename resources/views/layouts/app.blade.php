<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-cloak
    x-data="{ darkMode: localStorage.getItem('dark') === 'true', isMobile: (window.innerWidth < 640) ? true : false }"
    x-init="$watch('darkMode', val => localStorage.setItem('dark', val));"
    x-bind:class="{ 'dark': darkMode }"
>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>

    <body 
        class="font-sans antialiased cursor-default dark:text-gray-400 text-xl bg-white dark:bg-gray-900 h-screen w-screen"
        :class="{ 'pattern-color-dark': darkMode, 'pattern-squares-light': !darkMode }"
        style="background-attachment: fixed;"
    >
        <x-banner />
        <div class="flex">
            <!-- sidebar -->
            <div class=" fixed h-full z-50">
                @livewire('sidebar')
            </div>

            <div class="w-full ml-[4rem]">
                <div class="fixed w-full z-40">
                    {{-- @livewire('navigation-menu') --}}
                    @livewire('top-header')
                </div>

                <div class=" mt-[3.3rem]">
                    <!-- Page Heading -->
                    @if (isset($header))
                    <header 
                        :class="{ 'pattern-color-blue-2-dark': darkMode, 'pattern-color-light': !darkMode }" 
                        {{-- class="border-b border-gray-200 dark:border-gray-700 bg-gray-300/20 dark:bg-gray-700/20" --}}
                    >
                        <div class="text-2xl sm:text-3xl md:text-4xl max-w-7xl mx-auto py-3 sm:py-6 lg:py-9 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                    @endif
                    <!-- content -->
                    <main class="w-full">
                        {{ $slot }}
                    </main>
                </div>
            </div>

        </div>


        @stack('modals')
        @livewireScripts
    </body>
    
</html>
