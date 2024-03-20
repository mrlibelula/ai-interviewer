<x-container>
    <div class="relative isolate overflow-hidden h-full md:h-screen xl:flex xl:items-center xl:gap-x-20 pt-16 xl:-mt-[3.83rem]">
        <svg viewBox="0 0 1024 1024" class="absolute left-1/2_ top-[40%] _top-2/3 -z-10 h-[75rem] w-[75rem] -translate-y-1/2 [mask-image:radial-gradient(closest-side,white,transparent)] sm:left-full sm:-ml-80 xl:left-1/2 xl:ml-0 xl:-translate-x-1/2 xl:translate-y-0" aria-hidden="true">
            <circle cx="512" cy="512" r="512" fill="url(#759c1415-0410-454c-8f7c-9a820de03641)" fill-opacity="0.7" />
            <defs>
                <radialGradient id="759c1415-0410-454c-8f7c-9a820de03641">
                    <stop stop-color="#974e20" />
                    <stop offset="1" stop-color="#974e20" />
                </radialGradient>
            </defs>
        </svg>
        <div class="mx-auto px-4 max-w-md text-center xl:mx-0 xl:flex-auto xl:py-32 xl:text-left">
            <h2 class="text-4xl font-bold tracking-tight text-black dark:text-white sm:text-5xl">
                Ready to ace your next interview?
            </h2>
            <p class="mt-6 leading-8 text-gray-900 dark:text-gray-300">
                Embrace the challenges ahead and kickstart your journey to success. Begin now and conquer every obstacle on your path to achievement!
            </p>
            <div class="mt-10 flex items-center justify-center gap-x-6 xl:justify-start">
                <a wire:navigate href="{{ route('interview') }}">
                    <x-button>Start Interview</x-button>
                </a>
                <a wire:navigate href="{{ route('dashboard') }}" class="text-sm sm:text-base md:text-xl font-semibold leading-6 text-black dark:text-white">
                    View dashboard <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
        <div class="relative mt-16 h-[30rem] xl:mt-8">
            <img class="absolute left-0 top-0 w-full m-4 xl:w-[50rem] max-w-none rounded-md bg-white/5 ring-1 ring-white/10" src="https://tailwindui.com/img/component-images/dark-project-app-screenshot.png" alt="App screenshot" width="1824" height="1080">
        </div>
        
    </div>
</x-container>