<button class="flex flex-col w-full p-4 gap-2 rounded border border-2 text-left hover:scale-[1.025] transition-all
    @if(isset($highlighting[$course->getId()]))
        @if($highlighting[$course->getId()] === 1)
            bg-info-200 border-info-500
        @elseif($highlighting[$course->getId()] === 2)
            bg-warning-200 border-warning-500
        @elseif($highlighting[$course->getId()] === 3)
            bg-surface-200 border-surface-500
        @else
            bg-primary-200 border-primary-500
        @endif
    @endif"
    data-course="{{ $course->getId() }}">
        <span class="flex w-full justify-center items-center">
            @include("components.icons.buttonload")
        </span>

        <span class="text-xl font-bold">
            {{ $course->getTitle() }}
        </span>

        <span class="flex flex-wrap gap-2">
            <span class="flex flex-row whitespace-nowrap">
                <span class="pl-2 pr-1 text-surface-100 bg-primary-500 rounded-l-full border border-primary-500">
                    {{ t("Participants") }}
                </span>
                <span class="pl-1 pr-2 bg-primary-200 rounded-r-full border border-primary-500">
                    {{ $course->getMinParticipants() }} / {{ count($course->getAssignedParticipants()) }} / {{ $course->getMaxParticipants() }}
                </span>
            </span>
            <span class="flex flex-row whitespace-nowrap">
                <span class="pl-2 pr-1 text-surface-100 bg-primary-500 rounded-l-full border border-primary-500">
                    {{ t("Clearance level") }}
                </span>
                <span class="pl-1 pr-2 bg-primary-200 rounded-r-full border border-primary-500">
                    {{ $course->getMinClearance() }} - {{ $course->getMaxClearance() }}
                </span>
            </span>
        </span>

        <span class="flex flex-col gap-1">
            @if(!empty($courseWarnings[$course->getId()]))
                @foreach($courseWarnings[$course->getId()] as $warning)
                    <span class="text-danger">
                        {{ $warning }}
                    </span>
                @endforeach
            @endif
        </span>
</button>
