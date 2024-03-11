<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-md shadow']) }}>
    <table class="table-fixed w-full bg-gray-200/50 dark:bg-gray-700/70">
        @isset($header)
        <thead>
            <tr class="text-sm bg-gray-50 dark:bg-gray-900 text-gray-400 dark:text-gray-500 cursor-default">
                {{ $header }}
            </tr>
        </thead>
        @endisset
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>