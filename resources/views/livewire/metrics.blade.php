<div class="flex flex-col gap-y-8">
    <x-heading>
        <x-heading-metrics>
            User Performance Statistics
        </x-heading-metrics>
    </x-heading>

    <x-container>
        
        <x-metrics-nav />
        
        <x-bold>Challenges completed</x-bold>
        <x-table>
            <x-slot:header>
                <th class="p-2 w-12">#</th>
                <th class="p-2 w-[15rem]">Challenge</th>
                <th class="p-2 w-[5.7rem]">Topic</th>
                <th class="p-2 w-[5.7rem]">Difficulty</th>
                <th class="p-2 w-[5rem]">Status</th>
                <th class="p-2 w-[5.7rem]">Language</th>
                <th class="p-2 w-[5.7rem]">Solved In</th>
                <th class="p-2 ">Attempts</th>
                <th class="p-2 ">XP</th>
                <th class="p-2 ">Extra</th>
                <th class="p-2 ">Total XP</th>
            </x-slot:header>
            @for ($i = 0; $i <= 7; $i++)
            <tr class=" hover:bg-gray-300 dark:hover:bg-gray-700 smooth-500 text-center text-base">
                <td class="py-3 px-4 text-center font-mono">{{ $i + 1 }}</td>
                <td class="py-3 px-4 text-left">Binary Search Tree</td>
                <td class="py-3 px-4">Topic</td>
                <td class="py-3 px-4">Diff</td>
                <td class="py-3 px-4">Stat</td>
                <td class="py-3 px-4">Lang</td>
                <td class="py-3 px-4">00:10:23</td>
                <td class="py-3 px-4">6</td>
                <td class="py-3 px-4 text-right_">10</td>
                <td class="py-3 px-4 text-right_">20</td>
                <td class="py-3 px-4 text-right_">30</td>
            </tr>
            @endfor
        </x-table>
    </x-container>
</div>