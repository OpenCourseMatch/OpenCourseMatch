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
                    <span class="pl-1 pr-2 bg-primary bg-opacity-10 rounded-r-full border border-primary">
                        {{ $course->getMinParticipants() }} / {{ $realParticipantCount }} / {{ $course->getMaxParticipants() }}
                    </span>
                </div>
                <div class="flex flex-row whitespace-nowrap">
                    <span class="pl-2 pr-1 text-primary-font bg-primary rounded-l-full border border-primary">
                        {{ t("Clearance level") }}
                    </span>
                    <span class="pl-1 pr-2 bg-primary bg-opacity-10 rounded-r-full border border-primary">
                        {{ $course->getMinClearance() }} - {{ $course->getMaxClearance() }}
                    </span>
                </div>
            </div>
        @endif
    </div>

    {{-- Warnings --}}
    @foreach($courseWarnings as $warning)
        @component("components.layout.infomessage", [
                "type" => InfoMessageType::WARNING
            ])
            {{ $warning }}
        @endcomponent
    @endforeach

    {{-- User list --}}
    <table id="users-table" class="stripe"
        @if($course !== null)
            data-table-ajax="{{ Router::generate("course-assignment-edit-courseoverview-table", [
                "course" => $course?->getId()
            ]) }}"
        @else
            data-table-ajax="{{ Router::generate("course-assignment-edit-courseoverview-table-unassigned") }}"
        @endif>
        <thead>
        <tr>
            <th></th>
            <th>{{ t("First name") }}</th>
            <th>{{ t("Last name") }}</th>
            <th>{{ t("Group") }}</th>
        </tr>
        </thead>
        <tbody>
            {{-- Contents filled by assignment/edit.js --}}
        </tbody>
    </table>

    <script type="module">
        import * as EditCourseAssignment from "{{ Router::staticFilePath("js/assignment/edit.js") }}";
        EditCourseAssignment.initCourseOverview({
            "Search...": "{{ t("Search...") }}",
            "Loading...": "{{ t("Loading...") }}",
            "No entries": "{{ t("No entries") }}",
            "Back": "{{ t("Back") }}",
            "Next": "{{ t("Next") }}"
        }, "{{ Router::generate("course-assignment-edit-moveaway-modal") }}");
    </script>

    <div hidden>
        <div id="course-leader-icon">
            @include("components.icons.courseleader")
        </div>
    </div>
</div>
