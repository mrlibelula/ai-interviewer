<li class="ml-6">
    <div x-data="{ open: false }">
        <div @click="open = !open" class="group flex cursor-pointer py-2">
            @if ($topic->children->count())
            <i x-cloak x-show="!open" class="caret right icon text-gray-400 dark:text-gray-500"></i>
            <i x-cloak x-show="open" class="caret down icon text-gray-400 dark:text-gray-500"></i>
            @else
            <div class="ml-[0.48rem] text-gray-400 dark:text-gray-500" >•&nbsp;&nbsp;</div>
            @endif
            <div class=" group-hover:text-orange-600 group-hover:dark:text-orange-400 smooth-300">
                {{ $topic->name }}
            </div>
        </div>
        @if($topic->children->isNotEmpty())
            <ul x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90">
            @foreach($topic->children as $child)
                @livewire('tree-node', ['topic' => $child, 'level' => $level + 1], key($child->id))
            @endforeach
            </ul>
        @endif
    </div>
</li>