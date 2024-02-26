<div class="flex items-center gap-x-4 justify-between py-3 px-4 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 w-full -ml-[4rem]">
    <div class="ml-[4rem] text-lg font-semibold whitespace-nowrap px-2.5 rounded-full bg-gray-200/50 dark:bg-gray-600 text-gray-600 dark:text-gray-400">
        A.I. Interviewer
    </div>
    <div class=" hidden md:flex border-r border-gray-200 dark:border-gray-700">
        &nbsp;
    </div>
    <div class=" hidden md:flex whitespace-nowrap text-base opacity-60 font-semibold text-gray-900 dark:text-gray-200">
        Assistant Project Manager
    </div>
    <div class=" hidden md:flex border-r border-gray-200 dark:border-gray-700">
        &nbsp;
    </div>
    <div class="w-full px-3">
        <x-input placeholder="Search" class="h-[2.25rem] w-full text-sm placeholder-gray-500 placeholder:text-sm" />
    </div>
    <div class=" hidden md:flex border-r border-gray-200 dark:border-gray-700">
        &nbsp;
    </div>
    <div class="flex items-center gap-x-4">
        <x-icon-cube class="w-7 h-7 {{ session('openai_status') ? 'text-green-500' : 'text-rose-500' }}" />
        <x-theme-switcher />
    </div>
</div>
