@props(['number' => 0, 'symbol' => '%', 'customCssValue' => null])
<div class="flex flex-col gap-y-4 items-center gap-x-6 text-center">
    <x-progress-circle value="{{ $number }}" symbol="{{ $symbol }}" customCssValue="{{ $customCssValue }}" />
    <div>{{ $slot }}</div>
</div>