@props(['divId' => 'chat-01', 'avatar' => '🤖', 'user' => 'Chatbot', 'color' => 'sky', 'role' => '', 'content' => '', 'speed' => 22, 'animate' => true])
@php
    $tw_colors = [
        'sky' => 'text-sky-700 dark:text-sky-400',
        'yellow' => 'text-yellow-700 dark:text-yellow-400',
        'emerald' => 'text-emerald-700 dark:text-emerald-400',
        'rose' => 'text-rose-700 dark:text-rose-400',
        'orange' => 'text-orange-700 dark:text-orange-400',
        'fuchsia' => 'text-fuchsia-700 dark:text-fuchsia-400',
        'violet' => 'text-violet-700 dark:text-violet-400',
        'purple' => 'text-purple-700 dark:text-violet-400',
        'pink' => 'text-pink-700 dark:text-violet-400',
        'green' => 'text-green-700 dark:text-green-400',
    ];
    $tw_color = $tw_colors[$color] ?? $tw_colors['green'];
@endphp
<div class="flex flex-col gap-y-1" wire:key="{{ $divId }}">
    <span class="{{ $tw_color }} font-semibold">{{ $avatar }} {{ $user }}:</span>
    <div
        class="tracking-wide leading-normal"
        id="{{ $divId }}"
        @if ($animate)
        x-data="{ text: {{ \Illuminate\Support\Js::from(\App\Tool::prepareAiAnswerString($content)) }}, speed: {{ (int) $speed }}, id: {{ \Illuminate\Support\Js::from($divId) }} }"
        x-init="slowTextDisplay(text, speed, id)"
        @else
        x-data="{ text: {{ \Illuminate\Support\Js::from(\App\Tool::prepareAiAnswerString($content)) }}, id: {{ \Illuminate\Support\Js::from($divId) }} }"
        x-init="textDisplay(text, id)"
        @endif
    ></div>
</div>
