@props(['time_limit'])

{{-- Single Alpine timer; classic vs IDE skins toggled by CSS --}}
<div
    x-data="countdownTimer('{{ $time_limit }}')"
    x-init="startTimer()"
    {{ $attributes->merge(['class' => 'countdown-timer']) }}
>
    {{-- Classic / Reader: spaced digit cells with teal separators --}}
    <div class="timer-boxed-ui inline-flex items-center justify-center gap-x-2 text-[1.5rem] text-gray-950 dark:text-gray-100 tracking-widest font-mono w-full tabular-nums">
        <span x-text="hours.padStart(2, '0')" class="inline-flex items-center justify-center min-w-[2.85rem] border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2"></span>
        <span class="text-teal-500 dark:text-teal-400/90 select-none leading-none" aria-hidden="true">:</span>
        <span x-text="minutes.padStart(2, '0')" class="inline-flex items-center justify-center min-w-[2.85rem] border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2"></span>
        <span class="text-teal-500 dark:text-teal-400/90 select-none leading-none" aria-hidden="true">:</span>
        <span x-text="seconds.padStart(2, '0')" class="inline-flex items-center justify-center min-w-[2.85rem] border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2"></span>
    </div>

    {{-- IDE: compact row inside XP table --}}
    <div class="timer-inline-ui hidden items-center justify-between gap-x-3 w-full bg-white dark:bg-black/50 px-3 py-2.5 rounded-md">
        <div class="flex items-center gap-x-2 min-w-0">
            <span class="relative flex h-2 w-2 shrink-0">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-60"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 whitespace-nowrap session-timer-label">
                Time left
            </span>
        </div>
        <div class="font-mono tracking-wider text-emerald-600 dark:text-emerald-400 font-semibold tabular-nums shrink-0 session-timer-value">
            <span x-text="hours.padStart(2, '0')"></span><span class="text-emerald-500/80 animate-pulse mx-0.5">:</span><span x-text="minutes.padStart(2, '0')"></span><span class="text-emerald-500/80 animate-pulse mx-0.5">:</span><span x-text="seconds.padStart(2, '0')"></span>
        </div>
    </div>

    @include('components.partials.countdown-timer-script')
</div>
