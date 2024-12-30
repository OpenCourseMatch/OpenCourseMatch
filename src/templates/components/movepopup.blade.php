<div>
    @if($leadingCourse !== null)
        <h3>
            {{ t("Leading course") }}
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <button class="flex flex-col w-full p-4 gap-2 bg-opacity-20 rounded border border-2 text-left hover:scale-[1.025] transition-all
                @if(isset($highlighting[$leadingCourse->getId()]))
                    @if($highlighting[$leadingCourse->getId()] === 1)
                        bg-warning border-warning
                    @elseif($highlighting[$leadingCourse->getId()] === 2)
                        bg-info border-info
                    @elseif($highlighting[$leadingCourse->getId()] === 3)
                        bg-gray border-gray
                    @else
                        bg-primary border-primary
                    @endif
                @endif">
                <span class="text-xl font-bold">
                    {{ $leadingCourse->getTitle() }}
                </span>

                <span class="flex flex-wrap gap-2">
                    <span class="flex flex-row whitespace-nowrap">
                        <span class="pl-2 pr-1 text-primary-font bg-primary rounded-l-full border border-primary">
                            {{ t("Participants") }}
                        </span>
                            <span class="pl-1 pr-2 bg-primary bg-opacity-10 rounded-r-full border border-primary">
                            {{ $leadingCourse->getMinParticipants() }} / {{ count($leadingCourse->getAssignedParticipants()) }} / {{ $leadingCourse->getMaxParticipants() }}
                        </span>
                    </span>
                    <span class="flex flex-row whitespace-nowrap">
                        <span class="pl-2 pr-1 text-primary-font bg-primary rounded-l-full border border-primary">
                            {{ t("Clearance level") }}
                        </span>
                            <span class="pl-1 pr-2 bg-primary bg-opacity-10 rounded-r-full border border-primary">
                            {{ $leadingCourse->getMinClearance() }} - {{ $leadingCourse->getMaxClearance() }}
                        </span>
                    </span>
                </span>

                <span class="flex flex-col gap-1">
                    @foreach($errors[$leadingCourse->getId()] as $error)
                        <span class="text-danger">
                            {{ $error }}
                        </span>
                    @endforeach
                </span>
            </button>
        </div>
    @endif
</div>