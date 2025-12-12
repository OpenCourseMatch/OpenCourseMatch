<div>
    {{-- General user information --}}
    <div class="flex flex-col sm:flex-row flex-wrap items-center gap-x-4 gap-y-2 mb-2">
        <h3>
            {{ $account->getFullName() }}
        </h3>
        <div class="flex flex-wrap gap-2">
            <div class="flex flex-row whitespace-nowrap">
                <span class="pl-2 pr-1 text-surface-100 bg-primary-500 rounded-l-full border border-primary-500">
                    {{ t("Group") }}
                </span>
                <span class="pl-1 pr-2 bg-primary-200 rounded-r-full border border-primary-500">
                    @if($account->getGroup() !== null)
                        {{ $account->getGroup()->getName() }}
                    @else
                        {{ t("Default group") }}
                    @endif
                </span>
            </div>
            <div class="flex flex-row whitespace-nowrap">
                <span class="pl-2 pr-1 text-surface-100 bg-primary-500 rounded-l-full border border-primary-500">
                    {{ t("Clearance level") }}
                </span>
                <span class="pl-1 pr-2 bg-primary-200 rounded-r-full border border-primary-500">
                    @if($account->getGroup() !== null)
                        {{ $account->getGroup()->getClearance() }}
                    @else
                        0
                    @endif
                </span>
            </div>
        </div>
    </div>

    {{-- Warnings --}}
    @foreach($userWarnings as $warning)
        @component("shells.generic.infomessage", [
            "type" => \struktal\InfoMessage\InfoMessageType::WARNING
        ])
            {{ $warning }}
        @endcomponent
    @endforeach

    <hr class="w-full h-px my-4 bg-surface-200 border-none">

    @if($leadingCourse !== null)
        <h3 class="mb-2 mt-4">
            {{ t("Leading course") }}
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-4">
            @include("ui.assignment.movecoursepreview", [
                "course" => $leadingCourse,
                "courseWarnings" => $courseWarnings,
                "highlighting" => $highlighting
            ])
        </div>
    @endif

    <h3 class="mb-2">
        {{ t("Chosen courses") }}
    </h3>

    @if(!empty($chosenCourses))
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-4">
            @foreach($chosenCourses as $course)
                @include("ui.assignment.movecoursepreview", [
                    "course" => $course,
                    "courseWarnings" => $courseWarnings,
                    "highlighting" => $highlighting
                ])
            @endforeach
        </div>
    @else
        @component("shells.generic.infomessage", [
            "type" => \struktal\InfoMessage\InfoMessageType::WARNING
        ])
            {{ t("This user has not chosen any courses.") }}
        @endcomponent
    @endif

    <h3 class="mb-2">
        {{ t("Other courses") }}
    </h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-4">
        {{-- Unassign option --}}
        <button class="flex flex-col w-full p-4 gap-2 rounded rounded-lg border border-2 text-left transition-colors bg-warning-200 border-warning-500 hover:bg-warning-300"
                data-course="">
            <span class="text-xl font-bold">
                {{ t("Unassign") }}
            </span>

            <span class="text-danger-500">
                {{ t("This will unassign the user from the course.") }}
            </span>
        </button>

        @foreach($otherCourses as $course)
            @include("ui.assignment.movecoursepreview", [
                "course" => $course,
                "courseWarnings" => $courseWarnings,
                "highlighting" => $highlighting
            ])
        @endforeach
    </div>

    <script type="module">
        import * as EditCourseAssignment from "{{ Router->staticFilePath("js/assignment/edit.js") }}";
        EditCourseAssignment.initMoveAwayModal("{{ Router->generate("course-assignment-edit-move-away", ["user" => $account->getId()]) }}");
    </script>
</div>
