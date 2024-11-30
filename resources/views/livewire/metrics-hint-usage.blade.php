<div x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" 
    x-init="
        window.darkMode = localStorage.getItem('dark') === 'true';
        setTimeout(() => {
            initPieChart();
            initLineChart();
            initBarChart();
        }, 100);
    "
    @difficulty-data-updated.window="initPieChart(); initLineChart(); initBarChart();"
    @theme-changed.window="window.darkMode = !window.darkMode; initPieChart(); initLineChart(); initBarChart();"
>
    <x-heading>
        <x-heading-metrics>
            <x-slot:subtitle>A.I. Hint Usage Metrics</x-slot:subtitle>
        </x-heading-metrics>
    </x-heading>

    <x-container>

        @livewire('metrics-nav')

        {{-- <x-bold>
            This page shows you how many hints you've used in your challenges, and how many hints you've used on average per challenge.
        </x-bold> --}}

        <!-- Grid layout for multiple charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Distribution Pie Chart -->
            <div class="bg-gray-400/20 dark:bg-gray-700/10 p-6 rounded-xl shadow-sm">
                <h3 class="text-lg font-semibold mb-4">Overall Hint Distribution</h3>
                <div class="h-[300px]">
                    <canvas id="hintDistributionChart"></canvas>
                </div>
            </div>

            <!-- Hints per Challenge Bar Chart -->
            <div class="bg-gray-400/20 dark:bg-gray-700/10 p-6 rounded-xl shadow-sm">
                <h3 class="text-lg font-semibold mb-4">Average Hints per Challenge</h3>
                <div class="h-[300px]">
                    <canvas id="hintsPerChallengeChart"></canvas>
                </div>
            </div>

            <!-- Hint Usage Timeline -->
            <div class="lg:col-span-2 bg-gray-400/20 dark:bg-gray-700/10 p-6 rounded-xl shadow-sm">
                <h3 class="text-lg font-semibold mb-4">Hint Usage Over Time</h3>
                <div class="h-[300px]">
                    <canvas id="hintTimelineChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            @foreach(['easy', 'medium', 'hard'] as $difficulty)
            <div class="bg-gray-400/20 dark:bg-gray-700/10 p-4 rounded-xl shadow-sm">
                <h4 class="text-sm font-semibold mb-2 capitalize">{{ $difficulty }} Challenges</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-2xl font-bold">{{ $stats[$difficulty]['avg_hints'] }}</div>
                        <div class="text-xs text-gray-500">Avg. Hints</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold">{{ $stats[$difficulty]['completion_rate'] }}%</div>
                        <div class="text-xs text-gray-500">Success Rate</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </x-container>

    <script>
        // Shared colors and styles
        var colors = {
            easy: 'rgba(47, 211, 153, 0.9)',
            medium: 'rgba(47, 149, 211, 0.9)',
            hard: 'rgba(211, 47, 109, 0.9)'
        };

        // Your existing pie chart with distribution
        var initPieChart = () => {
            const canvas = document.getElementById('hintDistributionChart');
            if (!canvas) return;

            const existingChart = Chart.getChart('hintDistributionChart');
            if (existingChart) {
                existingChart.destroy();
            }

            new Chart(canvas, {
                type: 'pie',
                data: {
                    labels: ['Easy', 'Medium', 'Hard'],
                    datasets: [{
                        data: [{{ $this->getHintChartData()['easy'] }}, {{ $this->getHintChartData()['medium'] }}, {{ $this->getHintChartData()['hard'] }}],
                        backgroundColor: [
                            'rgba(47, 211, 153, 0.6)',    // Main green for Easy
                            'rgba(47, 149, 211, 0.6)',    // Blue-ish for Medium
                            'rgba(211, 47, 109, 0.6)'     // Pink-ish for Hard
                        ],
                        borderColor: [
                            'rgb(47, 211, 153)',          // Solid green
                            'rgb(47, 149, 211)',          // Solid blue
                            'rgb(211, 47, 109)'           // Solid pink
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Hint Usage Distribution by Difficulty Level',
                            color: window.darkMode ? '#e2e8f0' : '#1a202c'
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: window.darkMode ? '#e2e8f0' : '#1a202c'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.raw / total) * 100).toFixed(1);
                                    return `${context.label}: ${context.raw} hints (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        };

        // New bar chart showing average hints per challenge
        var initBarChart = () => {
            const ctx = document.getElementById('hintsPerChallengeChart');
            if (!ctx) return;

            const existingChart = Chart.getChart(ctx);
            if (existingChart) existingChart.destroy();

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Easy', 'Medium', 'Hard'],
                    datasets: [{
                        label: 'Average Hints Used',
                        data: [
                            {{ $this->getAverageHints()['easy'] }}, 
                            {{ $this->getAverageHints()['medium'] }}, 
                            {{ $this->getAverageHints()['hard'] }}
                        ],
                        backgroundColor: Object.values(colors)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            grid: {
                                color: window.darkMode ? 'rgba(226, 232, 240, 0.1)' : 'rgba(26, 32, 44, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                color: window.darkMode ? 'rgba(226, 232, 240, 0.1)' : 'rgba(26, 32, 44, 0.1)'
                            }
                        }
                    }
                }
            });
        };

        // Timeline chart showing hint usage over time
        var initLineChart = () => {
            const ctx = document.getElementById('hintTimelineChart');
            if (!ctx) return;

            const existingChart = Chart.getChart(ctx);
            if (existingChart) existingChart.destroy();

            // Debug data before creating chart
            console.log('Chart Data:', {
                labels: {!! json_encode($this->getTimelineLabels()) !!},
                easy: {!! json_encode($this->getTimelineData('easy')) !!},
                medium: {!! json_encode($this->getTimelineData('medium')) !!},
                hard: {!! json_encode($this->getTimelineData('hard')) !!}
            });

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($this->getTimelineLabels()) !!},
                    datasets: [{
                        label: 'Easy',
                        data: {!! json_encode($this->getTimelineData('easy')) !!},
                        borderColor: 'rgb(47, 211, 153)',
                        backgroundColor: 'rgb(47, 211, 153)',
                        borderWidth: 2,
                        tension: 0.4
                    }, {
                        label: 'Medium',
                        data: {!! json_encode($this->getTimelineData('medium')) !!},
                        borderColor: 'rgb(47, 149, 211)',
                        backgroundColor: 'rgb(47, 149, 211)',
                        borderWidth: 2,
                        tension: 0.4
                    }, {
                        label: 'Hard',
                        data: {!! json_encode($this->getTimelineData('hard')) !!},
                        borderColor: 'rgb(211, 47, 109)',
                        backgroundColor: 'rgb(211, 47, 109)',
                        borderWidth: 2,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: window.darkMode ? 'rgba(226, 232, 240, 0.1)' : 'rgba(26, 32, 44, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                color: window.darkMode ? 'rgba(226, 232, 240, 0.1)' : 'rgba(26, 32, 44, 0.1)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                color: window.darkMode ? '#e2e8f0' : '#1a202c'
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        };
    </script>
</div>
