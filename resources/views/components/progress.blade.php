@props(['number' => 0])
<div class="flex flex-col gap-y-4 items-center gap-x-6 text-center">
    <x-progress-circle value="{{ $number }}" />
    <div>{{ $slot }}</div>
</div>