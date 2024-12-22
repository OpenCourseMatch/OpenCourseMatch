<div>
    {{-- General course information --}}
    <div class="flex flex-col sm:flex-row flex-wrap items-center gap-x-4 gap-y-2 mb-2">
        <h2>
            @if($course !== null)
                {{ $course->getTitle() }}
            @else
                {{ t("Unassigned users") }}
            @endif
        </h2>
        @if($course !== null)
            <div class="flex flex-wrap gap-2">
                <div class="flex flex-row whitespace-nowrap">
                    <span class="pl-2 pr-1 text-primary-font bg-primary rounded-l-full border border-primary">
                        {{ t("Participants") }}
                    </span>
                    <span class="pl-1 pr-2 rounded-r-full border border-primary">
                        {{ $course->getMinParticipants() }} / {{ $realParticipantCount }} / {{ $course->getMaxParticipants() }}
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

    {{-- Warnings --}}
    @if($course !== null)
        @if($course->isCancelled())
            @component("components.layout.infomessage", [
                "type" => InfoMessageType::WARNING
            ])
                {{ t("This course has been cancelled.") }}
            @endcomponent
        @else
            @if($course->getMaxParticipants() < $realParticipantCount)
                @component("components.layout.infomessage", [
                    "type" => InfoMessageType::WARNING
                ])
                    {{ t("The number of participants exceeds the maximum number of participants allowed for this course.") }}
                @endcomponent
            @endif

            @if($course->getMinParticipants() > $realParticipantCount)
                @component("components.layout.infomessage", [
                    "type" => InfoMessageType::WARNING
                ])
                    {{ t("The number of participants is below the minimum number of participants required for this course.") }}
                @endcomponent
            @endif

            {{-- TODO: Warning if not all course leaders are assigned to this course --}}
        @endif
    @endif

    {{-- User list --}}
    {{-- TODO --}}
</div>
