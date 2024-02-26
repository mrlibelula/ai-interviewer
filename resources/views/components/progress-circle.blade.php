@props(['color' => '#84cc16', 'value' => 0, 'symbol' => '%'])
<div>
    <div class="flex items-center justify-center w-[5rem] h-[5rem] rounded-full shadow-lg border-[2px] border-gray-200 dark:border-gray-700" 
        style="background: conic-gradient({{ $color }} {{ $value }}%, transparent {{ $value !== 0 ? 3 : $value }}%);"
    >
        <div class="flex items-center justify-center w-[4.3rem] h-[4.3rem] rounded-full bg-gray-100/80 dark:bg-gray-800/80 text-xl text-gray-900 dark:text-gray-300 font-mono">
            {{ $value }}{{ $symbol }}
        </div>
    </div>
</div>