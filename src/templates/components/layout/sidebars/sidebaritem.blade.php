<li class="w-full">
    <a href="{{ $href }}" class="flex items-center p-2 gap-4 rounded hover:bg-surface-300 transition-colors @if(isset($active) && $active) bg-surface-300 font-bold @endif">
        @if(isset($icon))
            @include($icon)
        @endif

        <span class="truncate">
            {!! $slot !!}
        </span>
    </a>
</li>
