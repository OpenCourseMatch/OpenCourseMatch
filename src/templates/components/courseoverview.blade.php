<div>
    <div class="flex flex-col sm:flex-row items-center gap-x-4 gap-y-2 flex-wrap">
        <h2>
            @if($course !== null)
                {{ $course->getTitle() }}
            @else
                {{ t("Unallocated users") }}
            @endif
        </h2>
        @if($course !== null)
            <div class="flex flex-wrap gap-2">
                <div class="flex flex-row whitespace-nowrap">
                            <span class="pl-2 pr-1 text-primary-font bg-primary rounded-l-full border border-primary">
                                {{ t("Participants") }}
                            </span>
                    <span class="pl-1 pr-2 rounded-r-full border border-primary">
                                {{ $course->getMinParticipants() }} / {{ count($users) }} / {{ $course->getMaxParticipants() }}
                            </span>
                </div>
                <div class="flex flex-row whitespace-nowrap">
                            <span class="pl-2 pr-1 text-primary-font bg-primary rounded-l-full border border-primary">
                                {{ t("Clearance level") }}
                            </span>
                    <span class="pl-1 pr-2 rounded-r-full border border-primary">
                                {{ $course->getMinClearance() }} - {{ $course->getMaxClearance() }}
                            </span>
                </div>
            </div>
        @endif
    </div>

    @if($course !== null && $course->isCancelled())
        @component("components.layout.infomessage", [
            "type" => InfoMessageType::WARNING
        ])
            {{ t("This course has been cancelled.") }}
        @endcomponent
    @endif

    <!-- TODO: Error messages -->
</div>
