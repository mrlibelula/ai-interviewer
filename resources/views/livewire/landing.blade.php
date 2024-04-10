<div class="relative w-full mx-auto px-8 max-w-7xl isolate overflow-hidden h-full md:h-screen xl:flex xl:items-center xl:gap-x-20 pt-16 xl:-mt-[3.83rem]">
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
        <img 
            class="absolute left-0 top-0 w-full m-4 xl:w-[50rem] max-w-none bg-white/5 ring-1_ rounded-md shadow ring-white/10 grayscale_" 
            :class="{ 'invert_': darkMode }"
            src="{{ \App\Tool::randomItem([
                "https://mir-s3-cdn-cf.behance.net/project_modules/1400_webp/464f94172268465.65a01be1af5ee.png",
                "https://cdn.pixabay.com/photo/2023/05/10/03/46/ai-generated-7982835_1280.png",
                "https://mir-s3-cdn-cf.behance.net/project_modules/max_3840/ee47a7193089229.65e63f2864588.png",
    
                "https://mir-s3-cdn-cf.behance.net/project_modules/2800_webp/3964bc175456361.6511890469cd7.jpg",
                "https://mir-s3-cdn-cf.behance.net/project_modules/2800_webp/0ba1da175456361.65118903acb42.jpg",
                "https://mir-s3-cdn-cf.behance.net/project_modules/2800_webp/8ebf2e175456361.6511890468c26.jpg",
                "https://mir-s3-cdn-cf.behance.net/project_modules/max_3840/9eab5e175456361.651189054122d.jpg",
                "https://mir-s3-cdn-cf.behance.net/project_modules/2800_webp/0c1310172268465.65a016c5a820b.jpg",
                "https://mir-s3-cdn-cf.behance.net/project_modules/2800_webp/d36b6c172268465.65a016c5a71b3.jpg",
                "https://mir-s3-cdn-cf.behance.net/project_modules/2800_webp/37cdb1172268465.65a016c5a6447.jpg",
    
                "https://mir-s3-cdn-cf.behance.net/project_modules/2800_webp/f30948172268465.65ef3e6487fdd.jpg",
                "https://mir-s3-cdn-cf.behance.net/project_modules/2800_webp/314b5f172268465.65ef3e6377a5a.jpg",
                "https://mir-s3-cdn-cf.behance.net/project_modules/2800_webp/0ce1f4172268465.65ef3e6378a07.jpg",
                "https://mir-s3-cdn-cf.behance.net/project_modules/max_3840/4bf96a172268465.65ef3e6649890.jpg",
                "https://mir-s3-cdn-cf.behance.net/project_modules/1400_webp/6ac2ac172268465.65ef3e65416c2.jpg",
                "https://mir-s3-cdn-cf.behance.net/project_modules/max_3840/3ea8e3172268465.65ef3e6646952.jpg",
                "https://mir-s3-cdn-cf.behance.net/project_modules/2800_webp/e6739d172268465.65ef3e654246a.jpg",
                
                "https://mir-s3-cdn-cf.behance.net/project_modules/1400_webp/1a5c78172268465.65a01be5a9089.jpg",
                "https://mir-s3-cdn-cf.behance.net/project_modules/1400_webp/9bec91172268465.65a01bef8201c.jpg",
                "https://mir-s3-cdn-cf.behance.net/project_modules/1400_webp/ad551e172268465.65a01bf0420cf.jpg",
                "https://mir-s3-cdn-cf.behance.net/project_modules/2800_webp/9128a7165908369.642884bb024e1.jpg",
                "https://mir-s3-cdn-cf.behance.net/project_modules/2800_webp/cb258a165908369.642886bd75f47.jpg",
    
                "https://mir-s3-cdn-cf.behance.net/project_modules/1400_webp/adf124179927969.653410c653d12.jpg",
                "https://mir-s3-cdn-cf.behance.net/project_modules/2800_webp/6f88fc172268465.65a013b808ff0.jpg",
                "https://mir-s3-cdn-cf.behance.net/project_modules/2800_webp/0f71eb172268465.65a0156d6ab6d.jpg",

            ]) }}"
            
            {{-- src="https://mir-s3-cdn-cf.behance.net/project_modules/1400_webp/464f94172268465.65a01be1af5ee.png"  --}}

            alt="" width="1824" height="1080"
        >
    </div>
    
</div>