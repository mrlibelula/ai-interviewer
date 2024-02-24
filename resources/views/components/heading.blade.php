@props(['hasBg' => false])
<header 
    @if ($hasBg)
    :class="{ 'pattern-color-blue-2-dark': darkMode, 'pattern-color-light': !darkMode }" 
    @endif
>
    <div {{ $attributes->merge(['class' => 'max-w-7xl mx-auto pt-6 pb-4 sm:pt-10 sm:pb-6 lg:pt-20 lg:pb-10 px-4 sm:px-6 lg:px-8']) }}>
        {{ $slot }}
    </div>
</header>