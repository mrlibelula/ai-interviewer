@props(['label' => 'per page', 'size' => 'text-base'])
<div class="flex items-center gap-x-2 w-full {{ $size }}">
    <div>
        <select {{ $attributes->merge(['wire:model.live' => 'per_page', 'class' => 'form-select2 ' . $size]) }}>
            @if (isset($slot) && trim($slot) !== '')
                {{ $slot }}
            @else
                <option value="3">3</option>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="15">15</option>
            @endif
        </select>
    </div>
    <div class=" opacity-60 -mt-[0.25rem]">
        {{ $label }}
    </div>
</div>