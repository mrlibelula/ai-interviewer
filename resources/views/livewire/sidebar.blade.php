<form method="POST" action="{{ route('logout') }}" x-data class="hidden sm:flex flex-col items-center h-full overflow-hidden">
    @csrf
    <div class="flex flex-col items-center w-16 h-full overflow-hidden text-gray-700 dark:text-gray-500 bg-gray-100 dark:bg-gray-800/30">
        <a wire:navigate class="flex items-center justify-center mt-3" href="/">
            {{-- <div class="material-icons my-auto text-3xl text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 via-rose-500 to-sky-400"> headset_mic </div> --}}
            {{-- <img class="w-9" src="https://cdn-icons-png.flaticon.com/512/4600/4600333.png" alt=""> --}}
            {{-- <img class="w-7" src="https://www.favicon.cc/logo3d/807099.png" alt=""> --}}
            {{-- <img class="w-9" src="https://www.pngkey.com/png/full/164-1649200_view-favicon-on-t-shirt-pixel-mario.png" alt=""> --}}

            <img class="w-8 py-[0.195rem] opacity-75 hover:opacity-100 smooth-300 invert hover:invert-0 animate-pulse" src="https://www.onlygfx.com/wp-content/uploads/2022/04/brain-icon-3.png" alt="">
            {{-- <img class="w-8 h-9 py-0.5 opacity-75 hover:opacity-100 smooth-300 invert hover:invert-0 animate-pulse" src="https://www.onlygfx.com/wp-content/uploads/2022/04/brain-icon-1.png" alt=""> --}}
            {{-- <img class="w-8 py-[0.125rem] opacity-75 hover:opacity-100 smooth-300 invert hover:invert-0" src="https://cdn-icons-png.freepik.com/512/6969/6969728.png" alt=""> --}}
            
        </a>
        <div class="flex flex-col items-center mt-3 border-t border-gray-300 dark:border-gray-700">
            <!-- dashboard -->
            <a wire:navigate href="{{ route('dashboard') }}" class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-gray-300 dark:hover:bg-gray-700">
                <x-icon-squares class="w-7 h-7 stroke-current" />
            </a>

            {{-- <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-gray-300 dark:hover:bg-gray-700" href="#">
                <svg class="w-7 h-7 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </a> --}}

            <!-- stats -->
            <a class="flex items-center justify-center w-12 h-12 mt-2 bg-gray-300_ _dark:bg-gray-700 rounded" href="#">
                <svg class="w-7 h-7 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </a>

            {{-- <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-gray-300 dark:hover:bg-gray-700" href="#">
                <svg class="w-7 h-7 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                </svg>
            </a> --}}
        </div>
        <div class="flex flex-col items-center mt-2 border-t border-gray-300 dark:border-gray-700">
            {{-- <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-gray-300 dark:hover:bg-gray-700" href="#">
                <svg class="w-7 h-7 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </a> --}}

            {{-- <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-gray-300 dark:hover:bg-gray-700" href="#">
                <svg class="w-7 h-7 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                </svg>
            </a> --}}

            {{-- <a class="relative flex items-center justify-center w-12 h-12 mt-2 hover:bg-gray-300" href="#">
                <svg class="w-7 h-7 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                </svg>
                <span class="absolute top-0 left-0 w-2 h-2 mt-2 ml-2 bg-emerald-500 animate-pulse rounded-full"></span>
            </a> --}}

            <!-- admin -->
            <a wire:navigate href="/user/profile" class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-gray-300 dark:hover:bg-gray-700">
                <x-icon-cog class="w-7 h-7" />
            </a>

            <!-- profile -->
            <a wire:navigate href="/user/profile" class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-gray-300 dark:hover:bg-gray-700">
                <svg class="w-7 h-7 stroke-current" data-slot="icon" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"></path>
                </svg>
            </a>
        </div>
        <a @click.prevent="$root.submit();" class="flex items-center justify-center w-16 h-16 mt-auto bg-gray-200 dark:bg-gray-800/50 hover:bg-gray-300 smooth-300" href="{{ route('logout') }}">
            <x-icon-logout class="w-7 h-7 stroke-current" />
        </a>
    </div>
</form>