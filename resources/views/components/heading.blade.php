@props(['hasBg' => false])
<header 
    @if ($hasBg)
    class="rounded-xl shadow my-6"
    :class="{ 'pattern-angular-dark': darkMode, 'pattern-angular-light': !darkMode }" 
    @endif
>
    <div {{ $attributes->merge(['class' => 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8']) }}>
        {{ $slot }}
    </div>
</header>