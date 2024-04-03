<div x-data="{ 
    timer: @entangle('timer'),
    timeUp: @entangle('time_up'),
    timeUpMessage: @entangle('time_up_message'),
    start: @entangle('start'),
}">
    <div x-init="setInterval(() => timer = !timeUp ? (start ? @this.calculateRemainingTime() : timer) : timeUpMessage, 1000)" class="flex flex-col gap-y-2">
        <div @click="start = !start" class="font-mono uppercase tracking-widest font-semibold cursor-pointer  text-emerald-600 dark:text-emerald-400 bg-gray-200 dark:bg-gray-700/50 py-1 px-0.5 rounded-md mb-8" x-text="start ? 'Pause' : 'Start'">
            <!-- toggle button -->
        </div>
        <span x-text="timer" class=" font-mono text-[2rem] tracking-widest"></span>
    </div>
    
</div>