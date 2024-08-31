<div>
    <x-heading>
        <x-heading-metrics>
            <x-slot:subtitle>Difficulty Statistics and A.I. Feedback</x-slot:subtitle>
        </x-heading-metrics>
    </x-heading>

    <x-container>
        
        @livewire('metrics-nav')

        <x-bold>Completed challenges by difficulty level</x-bold>

        <!-- datatable nav -->
        <div class="flex items-center justify-between gap-x-1 md:gap-x-4">
            <div class=" text-sm flex items-center gap-x-2 border border-emerald-600 dark:border-emerald-500 rounded-lg w-fit pl-4">
                <div class=" text-emerald-600 dark:text-emerald-400">
                    Difficulty: 
                </div>
                <select wire:model.live="selectedDifficulty" class="form-select-trans">
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                </select>
            </div>
            <div class=" w-[20%]">
                <x-per-page />
            </div>
            <div class=" w-[60%]">
            @if (count($diffChallenges))
                {{ $diffChallenges->links() }}
            @endif
            </div>
        </div>

        <x-table2 color="black">
            @php
                $page = $diffChallenges->currentPage(); // Current page number
                $perPage = $diffChallenges->perPage(); // Items per page
            @endphp
            <x-slot:header>
                <th class="p-1">#</th>
                <th class="p-1 px-4 text-left">Challenge</th>
                <th class="p-1">Solved at</th>
                <th class="p-1">Time limit</th>
                <th class="p-1">Solved in</th>
                <th class="p-1">Attempts</th>
                <th class="p-1">XP</th>
                <th class="p-1">Extra</th>
                <th class="p-1">Total XP</th>
            </x-slot:header>
            @foreach ($diffChallenges as $challenge)
            @php
                $rowNumber = ($page - 1) * $perPage + $loop->index + 1;
            @endphp
            <tr class="table-row-2" wire:navigate href="{{ route('interview-start', [
                'enc_selected_difficulty' => \App\Tool::encode($challenge->difficulty_name),
                'enc_selected_topic_id' => \App\Tool::encode($challenge->topic_id),
                'enc_challenge_id' => \App\Tool::encode($challenge->id),
                'challenge_slug' => $challenge->challenge_slug,
            ]) }}">
                <td class="py-3 px-2">{{ $rowNumber }}</td>
                <td class="py-3 px-4 leading-tight text-left">
                    <div class=" text-base font-semibold text-black dark:text-gray-300 leading-tight">
                        {{ $challenge->title }}
                    </div>
                    <div class=" hidden md:block">
                        {{ $challenge->topic_name }} ({{ $challenge->difficulty_name }})
                    </div>
                    <div class="opacity-60">
                        {{ $challenge->language_name ?? '(no code)' }}
                    </div>
                </td>
                <td class=" whitespace-nowrap px-2">
                    {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $challenge->solved_at)->format('M d, Y - H:i') }}
                </td>
                <td class=" whitespace-nowrap px-2">
                    {{ $challenge->time_limit }}
                </td>
                <td class=" whitespace-nowrap px-2">
                    {{ \App\Tool::secondsToString($challenge->solved_time_seconds) }}
                </td>
                <td>{{ $challenge->attempts }}</td>
                <td>{{ $challenge->bonus_xp }}</td>
                <td>{{ $challenge->extra_bonus }}</td>
                <td class=" font-semibold text-emerald-500 dark:text-emerald-400">{{ $challenge->total_bonus }}</td>
            </tr>
            @endforeach
        </x-table2>

        <!-- datatable nav -->
        <div class="flex items-center justify-between gap-x-1 md:gap-x-4">
            <div class=" text-sm flex items-center gap-x-2 border border-emerald-600 dark:border-emerald-500 rounded-lg w-fit pl-4">
                <div class=" text-emerald-600 dark:text-emerald-400">
                    Difficulty: 
                </div>
                <select wire:model.live="selectedDifficulty" class="form-select-trans">
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                </select>
            </div>
            <div class=" w-[20%]">
                <x-per-page />
            </div>
            <div class=" w-[60%]">
            @if (count($diffChallenges))
                {{ $diffChallenges->links() }}
            @endif
            </div>
        </div>

        <ul style="list-style-type: square;" class="flex flex-col gap-y-4">
            <li>
                Challenge Difficulty Level: Categorize challenges by difficulty (e.g., Easy, Medium, Hard) and track the number of attempts and success rate for each level.
            </li>
            <li>
                Average Time to Solve by Difficulty: Record the average time taken to solve challenges at each difficulty level.
            </li>
            <li>
                Hint Usage by Difficulty: Track how often users request hints or help at each difficulty level, providing insights into where they struggle most.
            </li>
            <li>
                Comparative Performance: Compare a user's performance on challenges of similar difficulty with the average performance of other users.
            </li>
            <li>
                AI Evaluation of Difficulty: Use AI to assess the complexity of code solutions and provide feedback on whether a challenge was appropriately difficult for the user’s skill level.
            </li>
        </ul>
    </x-container>
</div>
