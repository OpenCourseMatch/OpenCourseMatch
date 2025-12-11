<div class="flex flex-col w-full p-4 gap-2 bg-surface-200 hover:bg-surface-300 rounded rounded-lg border border-2 border-surface-500 cursor-pointer transition-colors"
     data-choice-index="{{ $choice }}" data-course-id="{{ $course->getId() }}">
    <div class="flex items-center justify-between">
        <div class="flex items-center justify-center shrink-0 w-10 h-10 bg-primary-500 rounded-full">
            @include("icons.course", [
                "class" => "w-2/3 h-2/3 fill-surface-100"
            ])
        </div>
        <span class="hidden px-2 py-1 bg-primary-700 text-surface-100 rounded-full" data-choice-note></span>
    </div>

    <p class="text-xl font-bold">
        {{ $course->getTitle() }}
    </p>

    <p>
        {{ $course->getOrganizer() }}
    </p>
</div>
