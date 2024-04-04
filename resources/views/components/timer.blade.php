<div x-data="timer()" x-init="startTimer()" {{ $attributes }}>
    <span x-text="hours.padStart(2, '0') + ':' + minutes.padStart(2, '0') + ':' + seconds.padStart(2, '0')"></span>
    <script>
        function timer() {
            return {
                hours: '00',
                minutes: '00',
                seconds: '00',
                startTimer() {
                    setInterval(() => {
                        this.seconds = this.increment(this.seconds);
                        if (this.seconds === '00') {
                            this.minutes = this.increment(this.minutes);
                            if (this.minutes === '00') {
                                this.hours = this.increment(this.hours);
                            }
                        }
                    }, 1000);
                },
                increment(value) {
                    const numericValue = parseInt(value, 10);
                    if (numericValue < 59) {
                        return (numericValue + 1).toString().padStart(2, '0');
                    } else {
                        return '00';
                    }
                }
            };
        }
    </script>
</div>