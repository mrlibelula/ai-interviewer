<script>
    if (typeof window.countdownTimer !== 'function') {
        window.countdownTimer = function countdownTimer(startTime) {
            const [initialHours, initialMinutes, initialSeconds] = startTime.split(':').map(Number)
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
                            window.dispatchEvent(new Event('timeLimitEnded'))
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
    }
</script>
