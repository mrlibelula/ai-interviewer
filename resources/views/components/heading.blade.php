@props(['hasBg' => false])
<header 
    @if ($hasBg)
    class="rounded-xl shadow my-6"
    :class="{ 'pattern-angular-dark': darkMode, 'pattern-angular-light': !darkMode }" 
    @endif
>
    <div {{ $attributes->merge(['class' => 'max-w-7xl mx-auto']) }}>
        {{ $slot }}
    </div>
</header>