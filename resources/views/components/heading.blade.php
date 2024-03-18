@props(['hasBg' => false])
<header 
    @if ($hasBg)
    class="mb-4 sm:mb-6 lg:mb-10 rounded-xl m-4 sm:m-6 shadow"
    :class="{ 'pattern-angular-dark': darkMode, 'pattern-angular-light': !darkMode }" 
    @endif
>
    <div {{ $attributes->merge(['class' => 'max-w-7xl mx-auto py-6 sm:py-10 lg:py-16 px-4 sm:px-6 lg:px-8']) }}>
        {{ $slot }}
    </div>
</header>