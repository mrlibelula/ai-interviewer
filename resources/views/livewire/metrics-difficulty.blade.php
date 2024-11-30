<div 
    x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" 
    x-init="
        window.darkMode = localStorage.getItem('dark') === 'true';
        setTimeout(() => initChart(), 100);
    "
    @difficulty-data-updated.window="initChart()"
    @theme-changed.window="window.darkMode = !window.darkMode; initChart()"
>
    <x-heading>
        <x-heading-metrics>
            <x-slot:subtitle>Difficulty Statistics and A.I. Feedback</x-slot:subtitle>
        </x-heading-metrics>
    </x-heading>

    <x-container>
        
        @livewire('metrics-nav')

        <!-- graphs -->
        <div class="flex flex-col gap-y-10 lg:flex-row lg:gap-y-0 items-center gap-x-[4rem] xl:gap-x-[6rem] py-4">
            <!-- Add specific dimensions to the chart container -->
            <div class="w-full h-[250px]">
                <canvas id="difficultyChart"></canvas>
            </div>
            
            <!-- progress circles -->
            <div class="w-full _lg:w-[40%] flex items-center gap-x-[6rem] justify-center lg:justify-start">
                <div class="flex flex-col gap-y-4 text-center text-[1.03rem] font-mono tracking-wide">
                    <div class=" text-emerald-600 dark:text-emerald-200 leading-tight whitespace-nowrap">
                        Easy
                    </div>
                    <x-progress :number="$easySuccessRate" />
                </div>
                <div class="flex flex-col gap-y-4 text-center text-[1.03rem] font-mono tracking-wide">
                    <div class=" text-emerald-600 dark:text-emerald-200 leading-tight whitespace-nowrap">
                        Medium
                    </div>
                    <x-progress :number="$mediumSuccessRate" />
                </div>
                <div class="flex flex-col gap-y-4 text-center text-[1.03rem] font-mono tracking-wide">
                    <div class=" text-emerald-600 dark:text-emerald-200 leading-tight whitespace-nowrap">
                        Hard
                    </div>
                    <x-progress :number="$hardSuccessRate" />
                </div>
            </div>
            <!-- ai comments -->
            {{-- <div class="w-full _lg:w-[60%] relative"> --}}
                <!-- feedback nav -->
                {{-- <div class="flex items-center gap-x-4 py-4 text-base justify-between leading-tight">
                    <button 
                        wire:click='toggleFeedbackNav("problem_specific")' 
                        @click.prevent="$dispatch('feedback-loader-on'); $dispatch('toggled-feedback-nav', { feedback_type: 'problem_specific' })"
                        class="link relative {{ $feedback_nav['problem_specific'] ? 'dark:text-emerald-200 font-semibold' : '' }} _dark:text-gray-500"
                    >
                        Problem-Specific Feedback
                        @if ($new_feedback['problem_specific'])
                        <!--red dot-->
                        <span class="absolute w-2 h-2 -right-[0.7rem] top-0 bg-rose-400 rounded-full opacity-75 animate-ping"></span>
                        <span class="absolute w-2 h-2 -right-[0.7rem] top-0 bg-rose-700 rounded-full"></span>
                        @endif
                    </button>
                    <button 
                        wire:click='toggleFeedbackNav("optimization")' 
                        @click.prevent="$dispatch('feedback-loader-on'); $dispatch('toggled-feedback-nav', { feedback_type: 'optimization' })"
                        class="link relative {{ $feedback_nav['optimization'] ? 'dark:text-emerald-200 font-semibold' : '' }} _dark:text-gray-500"
                    >
                        Optimization Suggestions
                        @if ($new_feedback['optimization'])
                        <!--red dot-->
                        <span class="absolute w-2 h-2 -right-[0.7rem] top-0 bg-rose-400 rounded-full opacity-75 animate-ping"></span>
                        <span class="absolute w-2 h-2 -right-[0.7rem] top-0 bg-rose-700 rounded-full"></span>
                        @endif
                    </button>
                    <button 
                        wire:click='toggleFeedbackNav("best_practices")' 
                        @click.prevent="$dispatch('feedback-loader-on'); $dispatch('toggled-feedback-nav', { feedback_type: 'best_practices' })"
                        class="link relative {{ $feedback_nav['best_practices'] ? 'dark:text-emerald-200 font-semibold' : '' }} _dark:text-gray-500"
                    >
                        Coding Style & Best Practices
                        @if ($new_feedback['best_practices'])
                        <!--red dot-->
                        <span class="absolute w-2 h-2 -right-[0.7rem] top-0 bg-rose-400 rounded-full opacity-75 animate-ping"></span>
                        <span class="absolute w-2 h-2 -right-[0.7rem] top-0 bg-rose-700 rounded-full"></span>
                        @endif
                    </button>
                </div> --}}
                <!-- ai feedback -->
                {{-- <div class="flex flex-col-reverse gap-y-8 lg:gap-y-0 lg:flex-row items-start gap-x-[4rem] py-5 px-7 dark:bg-emerald-500/10 border-2 border-dotted border-emerald-500/60 bg-emerald-300/20 dark:border-emerald-200/40 w-full rounded-xl text-lg text-emerald-700 dark:text-emerald-300/70">
                    <div class=" relative w-full lg:w-[80%] flex items-center gap-x-4 text-base">
                        <textarea 
                            wire:model='ai_feedback'
                            rows="5" 
                            disabled 
                            style="resize: none;" 
                            class="w-full relative pl-0 _pt-2 py-0 bg-transparent border-none"
                        ></textarea>
                        <!-- text fade -->
                        <!--<div class=" absolute top-0 h-[1.5rem] w-full" :class="darkMode ? 'fade-feedback-dark' : 'fade-feedback-light'">
                            &nbsp;
                        </div>-->
                        
                        <!-- feedback spinner -->
                        <div x-cloak x-show="spinner" class="absolute top-0 left-0 z-30 h-full bg-gray-100 dark:bg-gray-800 overflow-hidden w-full flex justify-center items-center">
                            <div :class="darkMode ? 'spinner-circle-dark' : 'spinner-circle-light'"></div>
                        </div>

                    </div>
                    <!-- chatbot logo -->
                    <div class="w-[20%] overflow-hidden rounded-full bg-gradient-to-br from-emerald-300/25 via-emerald-600/70 to-emerald-500 dark:from-emerald-950/10 dark:via-emerald-300/20 dark:to-emerald-700">
                        <img class="p-4 w-full" src="{{ asset('/images/chatbot-icon.png') }}" alt="">
                        <!-- <img class="p-4 w-full" src="https://static.vecteezy.com/system/resources/previews/021/617/336/original/chatbot-3d-render-icon-illustration-png.png" alt=""> -->
                        <!-- <img class="p-6 w-full" src="https://static.vecteezy.com/system/resources/previews/012/628/405/original/chatbot-3d-render-icon-illustration-png.png" alt=""> -->
                    </div>
                </div> --}}
            {{-- </div> --}}
        </div>

        <x-bold>Completed challenges by difficulty level</x-bold>

        <!-- datatable nav -->
        <div class="flex items-center justify-between gap-x-1 md:gap-x-4">
            <div class=" text-sm flex items-center gap-x-2 bg-gray-200 dark:bg-gray-800 rounded-lg w-fit pl-4">
                <div class=" text-gray-600 dark:text-gray-400">
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

        <x-table2 color="gray">
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
                <td class=" font-semibold text-gray-500 dark:text-gray-400">{{ $challenge->total_bonus }}</td>
            </tr>
            @endforeach
        </x-table2>

        <!-- datatable nav -->
        <div class="flex items-center justify-between gap-x-1 md:gap-x-4">
            <div class=" text-sm flex items-center gap-x-2 bg-gray-200 dark:bg-gray-800 rounded-lg w-fit pl-4">
                <div class=" text-gray-600 dark:text-gray-400">
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

    </x-container>

    <script>
        var formatTime = (minutes) => {
            const hours = Math.floor(minutes / 60);
            const mins = Math.floor(minutes % 60);
            const secs = Math.floor((minutes * 60) % 60);
            
            return `${hours.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        };

        var initChart = () => {
            // First check if the canvas element exists
            const canvas = document.getElementById('difficultyChart');
            if (!canvas) return; // Exit if canvas doesn't exist

            // Destroy existing chart if it exists
            const existingChart = Chart.getChart('difficultyChart');
            if (existingChart) {
                existingChart.destroy();
            }

            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: ['Easy', 'Medium', 'Hard'],
                    datasets: [{
                        label: 'Average Time',
                        data: [{{ $this->getChartData()['easy'] }}, {{ $this->getChartData()['medium'] }}, {{ $this->getChartData()['hard'] }}],
                        backgroundColor: [
                            'rgba(47, 211, 153, 0.5)',
                            'rgba(47, 211, 153, 0.7)',
                            'rgba(47, 211, 153, 0.9)'
                        ],
                        borderColor: [
                            'rgb(47, 211, 153)',
                            'rgb(47, 211, 153)',
                            'rgb(47, 211, 153)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: Math.max(
                                {{ $this->getChartData()['easy'] }}, 
                                {{ $this->getChartData()['medium'] }}, 
                                {{ $this->getChartData()['hard'] }}
                            ) * 1.05,  // Adds 5% more space at the top
                            title: {
                                display: true,
                                text: 'Time (HH:MM:SS)',
                                color: window.darkMode ? '#e2e8f0' : '#1a202c'
                            },
                            ticks: {
                                color: window.darkMode ? '#e2e8f0' : '#1a202c',
                                callback: function(value) {
                                    return formatTime(value);
                                }
                            },
                            grid: {
                                color: window.darkMode ? 'rgba(226, 232, 240, 0.1)' : 'rgba(26, 32, 44, 0.1)'
                            }
                        },
                        x: {
                            ticks: {
                                color: window.darkMode ? '#e2e8f0' : '#1a202c'
                            },
                            grid: {
                                color: window.darkMode ? 'rgba(226, 232, 240, 0.1)' : 'rgba(26, 32, 44, 0.1)'
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Average Time to Solve Challenges by Difficulty Level',
                            color: window.darkMode ? '#e2e8f0' : '#1a202c'
                        },
                        legend: {
                            labels: {
                                color: window.darkMode ? '#e2e8f0' : '#1a202c'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Avg. time: ${formatTime(context.raw)}`;
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
</div>
