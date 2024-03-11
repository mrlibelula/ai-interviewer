<div {{ $attributes->merge(['class' => 'h-60 flex flex-col gap-y-8 justify-center items-center text-gray-800 dark:text-gray-200']) }}>
    <div class="text-5xl">
        ¯\_(ツ)_/¯
    </div>
    <div>
        {{ $slot ?? 'Nothing found' }}
    </div>
</div>