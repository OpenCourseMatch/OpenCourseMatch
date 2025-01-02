<button class="flex flex-col w-full p-4 gap-2 bg-opacity-20 rounded border border-2 text-left hover:scale-[1.025] transition-all
    @if(isset($highlighting[$course->getId()]))
        @if($highlighting[$course->getId()] === 1)
            bg-info border-info
        @elseif($highlighting[$course->getId()] === 2)
            bg-warning border-warning
        @elseif($highlighting[$course->getId()] === 3)
            bg-gray border-gray
        @else
            bg-primary border-primary
        @endif
    @endif"
    data-course="{{ $course->getId() }}">
        <span class="text-xl font-bold">
            {{ $course->getTitle() }}
        </span>

        <span class="flex flex-wrap gap-2">
            <span class="flex flex-row whitespace-nowrap">
                <span class="pl-2 pr-1 text-primary-font bg-primary rounded-l-full border border-primary">
                    {{ t("Participants") }}
                </span>
                <span class="pl-1 pr-2 bg-primary bg-opacity-10 rounded-r-full border border-primary">
                    {{ $course->getMinParticipants() }} / {{ count($course->getAssignedParticipants()) }} / {{ $course->getMaxParticipants() }}
                </span>
            </span>
            <span class="flex flex-row whitespace-nowrap">
                <span class="pl-2 pr-1 text-primary-font bg-primary rounded-l-full border border-primary">
                    {{ t("Clearance level") }}
                </span>
                <span class="pl-1 pr-2 bg-primary bg-opacity-10 rounded-r-full border border-primary">
                    {{ $course->getMinClearance() }} - {{ $course->getMaxClearance() }}
                </span>
            </span>
        </span>

        <span class="flex flex-col gap-1">
            @foreach($errors[$course->getId()] as $error)
                <span class="text-danger">
                    {{ $error }}
                </span>
            @endforeach
        </span>
</button>
