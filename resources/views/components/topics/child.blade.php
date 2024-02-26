@props(['children'])
<div>
    @foreach ($children as $child)
    <div>
        <i class="caret right icon text-gray-400 dark:text-gray-600"></i>
        {{ $child->name }}
        @if ($child->children->count() > 0)
            <x-topics.child :children="$child->children" />
        @endif
    </div>
    @endforeach
</div>