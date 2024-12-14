<div x-data="metricsData()" x-init="$nextTick(() => init())">
    <x-heading>
        <x-heading-metrics>
            <x-slot:subtitle>A.I. Topic Usage Metrics</x-slot:subtitle>
        </x-heading-metrics>
    </x-heading>

    <x-container>
        @livewire('metrics-nav')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
            <!-- Challenges by Topics -->
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">Challenges by Topics</h3>
                <canvas id="topicsChart"></canvas>
            </div>

            <!-- Attempts per Topic -->
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">Average Attempts per Topic</h3>
                <canvas id="attemptsChart"></canvas>
            </div>

            <!-- Average Completion Time -->
            <div class="bg-white dark:bg-gray-800 col-span-2 p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">Average Completion Time</h3>
                <canvas id="timeChart"></canvas>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400">Most Popular Topic</h4>
                <p class="text-2xl font-bold">{{ $mostPopularTopic }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400">Highest Attempt Rate</h4>
                <p class="text-2xl font-bold">{{ $highestAttemptTopic }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400">Fastest Completion</h4>
                <p class="text-2xl font-bold">{{ $fastestCompletionTopic }}</p>
            </div>
        </div>
    </x-container>
</div>

<script>
    function metricsData() {
        return {
            charts: {},
            darkMode: localStorage.getItem('dark') === 'true',
            init() {
                // Wait for Chart.js to be loaded
                if (typeof Chart === 'undefined') {
                    setTimeout(() => this.init(), 100);
                    return;
                }
                this.initCharts();
            },
            destroyCharts() {
                if (this.charts.topics) this.charts.topics.destroy();
                if (this.charts.attempts) this.charts.attempts.destroy();
                if (this.charts.time) this.charts.time.destroy();
                this.charts = {};
            },
            initCharts() {
                this.destroyCharts();

                const chartConfig = {
                    topics: {
                        type: 'bar',
                        data: {
                            labels: @json($topicLabels ?? []),
                            datasets: [{
                                label: 'Solved Challenges',
                                data: @json($topicCounts ?? []),
                                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    },
                    attempts: {
                        type: 'line',
                        data: {
                            labels: @json($topicLabels ?? []),
                            datasets: [{
                                label: 'Average Attempts per Challenge',
                                data: @json($avgAttempts ?? []),
                                borderColor: 'rgba(255, 99, 132, 1)',
                                tension: 0.1
                            }]
                        },
                        options: {
                            responsive: true
                        }
                    },
                    time: {
                        type: 'radar',
                        data: {
                            labels: @json($topicLabels ?? []),
                            datasets: [{
                                label: 'Average Completion Time (minutes)',
                                data: @json($avgCompletionTimes ?? []),
                                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                borderColor: 'rgba(153, 102, 255, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true
                        }
                    }
                };

                // Initialize charts only if elements exist
                const topicsCtx = document.getElementById('topicsChart');
                if (topicsCtx) {
                    try {
                        this.charts.topics = new Chart(topicsCtx, chartConfig.topics);
                    } catch (e) {
                        console.error('Failed to create topics chart:', e);
                    }
                }

                const attemptsCtx = document.getElementById('attemptsChart');
                if (attemptsCtx) {
                    try {
                        this.charts.attempts = new Chart(attemptsCtx, chartConfig.attempts);
                    } catch (e) {
                        console.error('Failed to create attempts chart:', e);
                    }
                }

                const timeCtx = document.getElementById('timeChart');
                if (timeCtx) {
                    try {
                        this.charts.time = new Chart(timeCtx, chartConfig.time);
                    } catch (e) {
                        console.error('Failed to create time chart:', e);
                    }
                }
            }
        }
    }
</script>

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush