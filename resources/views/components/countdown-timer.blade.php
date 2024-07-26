@props(['time_limit'])
<div 
    x-data="countdownTimer('{{ $time_limit }}')" 
    x-init="startTimer()" {{ $attributes }} 
>
    {{-- <span x-text="hours.padStart(2, '0') + ':' + minutes.padStart(2, '0') + ':' + seconds.padStart(2, '0')"></span> --}}
    <span id="hours" x-text="hours.padStart(2, '0')" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 whitespace-nowrap"></span>
    <span class=" -mx-3 text-emerald-400 dark:text-emerald-500 animate-pulse">:</span>
    <span id="minutes" x-text="minutes.padStart(2, '0')" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 whitespace-nowrap"></span>
    <span class=" -mx-3 text-emerald-400 dark:text-emerald-500 animate-pulse">:</span>
    <span id="seconds" x-text="seconds.padStart(2, '0')" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 whitespace-nowrap"></span>
    <script>
        function countdownTimer(startTime) {
            const [ initialHours, initialMinutes, initialSeconds ] = startTime.split(':').map(Number)
            let timerInterval
            window.addEventListener('stop-timer', () => {
                clearInterval(timerInterval)
            })
            return {
                hours: initialHours.toString().padStart(2, '0'),
                minutes: initialMinutes.toString().padStart(2, '0'),
                seconds: initialSeconds.toString().padStart(2, '0'),
                startTimer() {
                    const totalSeconds = initialHours * 3600 + initialMinutes * 60 + initialSeconds
                    let remainingSeconds = totalSeconds
                    timerInterval = setInterval(() => {
                        remainingSeconds--
                        this.hours = Math.floor(remainingSeconds / 3600).toString().padStart(2, '0')
                        this.minutes = Math.floor((remainingSeconds % 3600) / 60).toString().padStart(2, '0')
                        this.seconds = (remainingSeconds % 60).toString().padStart(2, '0')
    
                        if (remainingSeconds <= 0) {
                            clearInterval(timerInterval)
                            const event = new Event('timeLimitEnded')
                            window.dispatchEvent(event)
                        }

                        Livewire.dispatch('currentElapsedTime', { 
                            hours: this.hours, 
                            minutes: this.minutes, 
                            seconds: this.seconds,
                        })

                    }, 1000)
                }
            }
        }
    </script>
</div>