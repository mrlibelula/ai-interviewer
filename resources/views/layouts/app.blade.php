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
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Icons -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/components/icon.min.css" integrity="sha512-rTyypI20S663Wq9zrzMSOP1MNPHaYX7+ug5OZ/DTqCDLwRdErCo2W30Hdme3aUzJSvAUap3SmBk0r5j0vRxyGw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Favicon -->
		<link rel="shortcut icon" href="{{ url(asset('images/libesoft.io_inv.png')) }}">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://code.jquery.com/jquery-3.6.2.min.js" integrity="sha256-2krYZKh//PcchRtd+H+VyyQoZ/e3EcrkxhM8ycwASPA=" crossorigin="anonymous"></script>

        @stack('meta')

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>

    <body 
        class="font-sans antialiased relative cursor-default dark:text-gray-400 text-xl bg-white dark:bg-gray-900 h-screen"
        {{-- :class="{ 'pattern-color-dark': darkMode, 'pattern-squares-light': !darkMode }" --}}
        style="background-attachment: fixed;"
        :style="'scrollbar-width: auto; ' + (darkMode ? 'scrollbar-color: #838a97 #161e2e;' : 'scrollbar-color: #374151 #ffffff;')"
    >
        <svg viewBox="0 0 1024 1024" class="absolute hidden lg:block left-1/2_ -top-[5rem] xl:-top-[30rem] top-[40%]_ _top-2/3 -z-10 h-[75rem]_ w-[68rem] -translate-y-1/2 [mask-image:radial-gradient(closest-side,white,transparent)] sm:left-full_ sm:-ml-80 _xl:left-1/2 left-1/4 xl:ml-0 xl:-translate-x-1/2 xl:translate-y-0" aria-hidden="true">
            <circle cx="512" cy="512" r="512" fill="url(#759c1415-0410-454c-8f7c-9a820de03641)" fill-opacity="0.7" />
            <defs>
                <radialGradient id="759c1415-0410-454c-8f7c-9a820de03641">
                    <!-- gray: #e1e4e8 - green: #b5cece -->
                    <stop :stop-color="darkMode ? '#434c5b' : '#dddee0'" />
                    <stop offset="1" :stop-color="darkMode ? '#434c5b' : '#dddee0'" />
                </radialGradient>
            </defs>
        </svg>
        <x-banner />
        <div class="flex">
            <!-- sidebar -->
            <div class=" fixed h-full z-50">
                @livewire('sidebar')
            </div>

            <div class="flex flex-col justify-between h-screen w-full sm:ml-[4rem]">
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
                    
                    
                </div>
                @if (!request()->routeIs('landing'))
                <footer class="p-page-x flex flex-col gap-y-2 justify-center items-center pt-14 lg:pt-20 pb-8">
                    <a href="https://libe.dev" target="_other_LIBEDEV" class="flex items-center">
                        <x-libe-dev-logo class=" scale-[.55]" />
                        <div class=" text-base text-gray-500">
                            {{ date('Y') }} - libe.dev
                        </div>
                    </a>
                </footer>
                @endif
            </div>

        </div>


        @stack('modals')
        
        @stack('scripts')
        
        @livewireScripts

        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        
        <script>
            $(document).ready(function() {
                toastr.options = {
                    progressBar: true,
                    positionClass: 'toast-top-center custom-toast-position',
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


        </script>

    </body>
    
</html>
