@props(['color' => '#2fd399', 'value' => 0, 'symbol' => '%'])
<div>
    <div class="flex items-center justify-center w-[8rem] h-[8rem] rounded-full shadow-lg border-[2px] border-gray-200 dark:border-gray-700" 
        style="background: conic-gradient({{ $color }} {{ $value }}%, transparent {{ $value !== 0 ? 3 : $value }}%);"
    >
        <div class="flex items-center justify-center w-[6.3rem] h-[6.3rem] rounded-full bg-emerald-100/80 dark:bg-emerald-800/80 text-2xl text-gray-900 dark:text-gray-300 font-mono">
            <span class=" mr-1">{{ $value }}</span>{{ $symbol }}
        </div>
    </div>
</div>