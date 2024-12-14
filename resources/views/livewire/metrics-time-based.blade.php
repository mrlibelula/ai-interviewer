<div
    x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" 
    x-init="
        window.darkMode = localStorage.getItem('dark') === 'true';
        setTimeout(() => initializeCharts(), 100);
    "
    @theme-changed.window="window.darkMode = !window.darkMode; initializeCharts()"
>
    <x-heading>
        <x-heading-metrics>
            <x-slot:subtitle>Time Based Metrics</x-slot:subtitle>
        </x-heading-metrics>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </x-heading>

    <x-container>
        @livewire('metrics-nav')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <!-- Time of Day Distribution -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 dark:text-white">Challenges Solved by Hour</h3>
                <div style="height: 300px;">
                    <canvas id="hourlyChart"></canvas>
                </div>
            </div>

            <!-- Day of Week Distribution -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 dark:text-white">Challenges Solved by Day</h3>
                <div style="height: 300px;">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>

            <!-- Monthly Distribution -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 dark:text-white">Challenges Solved by Month</h3>
                <div style="height: 300px;">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            <!-- Average Completion Time by Difficulty -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 dark:text-white">Average Completion Time by Difficulty</h3>
                <div style="height: 300px;">
                    <canvas id="difficultyChart"></canvas>
                </div>
            </div>
        </div>
    </x-container>

    <script>
        function formatTime(seconds) {
            return new Date(seconds * 1000).toISOString().substr(11, 8);
        }

        // Common chart options
        function getChartOptions(title = '') {
            return {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: window.darkMode ? '#e2e8f0' : '#1a202c'
                        },
                        grid: {
                            color: window.darkMode ? 'rgba(226, 232, 240, 0.1)' : 'rgba(26, 32, 44, 0.1)'
                        },
                        title: {
                            display: true,
                            text: 'Number of Challenges',
                            color: window.darkMode ? '#e2e8f0' : '#1a202c'
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
                        text: title,
                        color: window.darkMode ? '#e2e8f0' : '#1a202c',
                        font: {
                            size: 16
                        }
                    },
                    legend: {
                        labels: {
                            color: window.darkMode ? '#e2e8f0' : '#1a202c'
                        }
                    }
                }
            };
        }

        function initializeCharts() {
            // Destroy existing charts if they exist
            ['hourlyChart', 'dailyChart', 'monthlyChart', 'difficultyChart'].forEach(id => {
                const existingChart = Chart.getChart(id);
                if (existingChart) {
                    existingChart.destroy();
                }
            });

            // Hourly Chart
            new Chart(document.getElementById('hourlyChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($solvedByHour->pluck('hour')->map(fn($hour) => sprintf("%02d:00", $hour))) !!},
                    datasets: [{
                        label: 'Challenges Solved',
                        data: {!! json_encode($solvedByHour->pluck('count')) !!},
                        backgroundColor: 'rgba(47, 211, 153, 0.5)',
                        borderColor: 'rgb(47, 211, 153)',
                        borderWidth: 1
                    }]
                },
                options: getChartOptions('Challenges Solved by Hour')
            });

            // Daily Chart
            new Chart(document.getElementById('dailyChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($solvedByDay->pluck('day')) !!},
                    datasets: [{
                        label: 'Challenges Solved',
                        data: {!! json_encode($solvedByDay->pluck('count')) !!},
                        backgroundColor: 'rgba(47, 211, 153, 0.7)',
                        borderColor: 'rgb(47, 211, 153)',
                        borderWidth: 1
                    }]
                },
                options: getChartOptions('Challenges Solved by Day')
            });

            // Monthly Chart
            new Chart(document.getElementById('monthlyChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($solvedByMonth->pluck('month')) !!},
                    datasets: [{
                        label: 'Challenges Solved',
                        data: {!! json_encode($solvedByMonth->pluck('count')) !!},
                        backgroundColor: 'rgba(47, 211, 153, 0.8)',
                        borderColor: 'rgb(47, 211, 153)',
                        borderWidth: 1
                    }]
                },
                options: getChartOptions('Challenges Solved by Month')
            });

            // Difficulty Chart
            new Chart(document.getElementById('difficultyChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($averageTimePerDifficulty->pluck('difficulty')) !!},
                    datasets: [{
                        label: 'Average Time',
                        data: {!! json_encode($averageTimePerDifficulty->pluck('avg_time')) !!},
                        backgroundColor: {!! json_encode($averageTimePerDifficulty->map(fn($diff) => 
                            $diff->difficulty === 'easy' ? 'rgba(47, 211, 153, 0.5)' : 
                            ($diff->difficulty === 'medium' ? 'rgba(47, 211, 153, 0.7)' : 'rgba(47, 211, 153, 0.9)')
                        )) !!},
                        borderColor: 'rgb(47, 211, 153)',
                        borderWidth: 1
                    }]
                },
                options: {
                    ...getChartOptions('Average Completion Time by Difficulty'),
                    scales: {
                        ...getChartOptions().scales,
                        y: {
                            ...getChartOptions().scales.y,
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
                            }
                        }
                    },
                    plugins: {
                        ...getChartOptions().plugins,
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Time: ${formatTime(context.raw)}`;
                                }
                            }
                        }
                    }
                }
            });
        }

        document.addEventListener('livewire:navigating', () => {
            // Destroy all charts before navigation
            const charts = Object.values(Chart.instances);
            charts.forEach(chart => chart.destroy());
        });

        document.addEventListener('livewire:navigated', () => {
            initializeCharts();
        });

        // Initialize charts on first load
        initializeCharts();

        // Update charts when theme changes
        window.addEventListener('theme-changed', function() {
            initializeCharts();
        });
    </script>
</div>