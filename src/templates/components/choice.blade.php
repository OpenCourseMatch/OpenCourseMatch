<div class="flex flex-col w-full p-4 gap-2 bg-primary bg-opacity-20 rounded border border-2 border-primary cursor-pointer hover:scale-[1.025] transition-all"
     data-choice-index="{{ $choice }}" data-course-id="{{ $course->getId() }}">
    <div class="flex items-center justify-between">
        <div class="flex items-center justify-center shrink-0 w-10 h-10 bg-secondary rounded-full">
            @include("components.icons.course", [
                "class" => "w-2/3 h-2/3 fill-secondary-font"
            ])
        </div>
        <span class="hidden px-2 py-1 bg-gray text-gray-font rounded-full" data-choice-note></span>
    </div>

    <p class="text-xl font-bold">
        {{ $course->getTitle() }}
    </p>

    <p>
        {{ $course->getOrganizer() }}
    </p>
</div>
