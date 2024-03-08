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

        <!-- Highlights -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js" integrity="sha512-rdhY3cbXURo13l/WU9VlaRyaIYeJ/KBakckXIvJNAQde8DgpOmE+eZf7ha4vdqVjTtwQt69bD2wH2LXob/LB7Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/ir-black.min.css" integrity="sha512-koyJNDRWiSkXWV9VpMDJXZnwgO5wB1VsSw5O7XpkYFBL9Yjp6QiotG1CPuD3iHbOw5a3obVP5guUzxhJA0Dmhw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/github-dark.min.css" integrity="sha512-rO+olRTkcf304DQBxSWxln8JXCzTHlKnIdnMUwYvQa9/Jd4cQaNkItIUj6Z4nvW1dqK0SKXLbn9h4KwZTNtAyw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script>hljs.highlightAll();</script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Icons -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/components/icon.min.css" integrity="sha512-rTyypI20S663Wq9zrzMSOP1MNPHaYX7+ug5OZ/DTqCDLwRdErCo2W30Hdme3aUzJSvAUap3SmBk0r5j0vRxyGw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://code.jquery.com/jquery-3.6.2.min.js" integrity="sha256-2krYZKh//PcchRtd+H+VyyQoZ/e3EcrkxhM8ycwASPA=" crossorigin="anonymous"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>

    <body 
        class="font-sans antialiased cursor-default dark:text-gray-400 text-xl bg-white dark:bg-gray-900 h-screen"
        {{-- :class="{ 'pattern-color-dark': darkMode, 'pattern-squares-light': !darkMode }" --}}
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

                <div class=" mt-[3.8rem]">
                    @if (isset($header))
                    <!-- Page Heading -->
                    <x-heading hasBg="{{ request()->routeIs('dashboard') ? false : false }}">
                        {{ $header }}
                    </x-heading>
                    @endif
                    <!-- content -->
                    <main class="w-full">
                        {{ $slot }}
                    </main>
                    
                    <footer class="p-page-x flex flex-col gap-y-2 justify-center items-center pt-14 lg:pt-20 pb-8">
                        <a href="https://libe.dev" target="_other_LIBEDEV" class="flex items-center">
                            <x-libe-dev-logo class=" scale-[.55]" />
                            <div class=" text-base text-gray-500">
                                {{ date('Y') }} - libe.dev
                            </div>
                        </a>
                    </footer>
                    
                </div>
            </div>

        </div>


        @stack('modals')
        @stack('scripts')

        @livewireScripts

        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        
        <script>
            $(document).ready(function() {
                toastr.options = {
                    progressBar: true,
                    positionClass: 'toast-top-right',
                    closeButton: true,
                    preventDuplicates: false,
                    showMethod: 'slideDown',
                }
            })

            window.addEventListener('success', event => {
                toastr.success(event.detail[0].message, event.detail[0].title)
            })
            
            window.addEventListener('warning', event => {
                toastr.warning(event.detail[0].message, event.detail[0].title)
            })
            
            window.addEventListener('error', event => {
                toastr.error(event.detail[0].message, event.detail[0].title)
            })
            
            window.addEventListener('info', event => {
                toastr.info(event.detail[0].message, event.detail[0].title)
            })


        </script> --}}

    </body>
    
</html>
