<div>
    {{-- General user information --}}
    <div class="flex flex-col sm:flex-row flex-wrap items-center gap-x-4 gap-y-2 mb-2">
        <h3>
            {{ $account->getFullName() }}
        </h3>
        <div class="flex flex-wrap gap-2">
            <div class="flex flex-row whitespace-nowrap">
                <span class="pl-2 pr-1 text-primary-font bg-primary rounded-l-full border border-primary">
                    {{ t("Group") }}
                </span>
                <span class="pl-1 pr-2 bg-primary bg-opacity-10 rounded-r-full border border-primary">
                    @if($account->getGroup() !== null)
                        {{ $account->getGroup()->getName() }}
                    @else
                        {{ t("Default group") }}
                    @endif
                </span>
            </div>
            <div class="flex flex-row whitespace-nowrap">
                <span class="pl-2 pr-1 text-primary-font bg-primary rounded-l-full border border-primary">
                    {{ t("Clearance level") }}
                </span>
                <span class="pl-1 pr-2 bg-primary bg-opacity-10 rounded-r-full border border-primary">
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
        @component("components.layout.infomessage", [
            "type" => InfoMessageType::WARNING
        ])
            {{ $warning }}
        @endcomponent
    @endforeach

    <hr class="w-full h-px my-4 bg-gray border-none">

    @if($leadingCourse !== null)
        <h3 class="mb-2 mt-4">
            {{ t("Leading course") }}
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-4">
            @include("assignment.components.edit.movecoursepreview", [
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
                @include("assignment.components.edit.movecoursepreview", [
                    "course" => $course,
                    "courseWarnings" => $courseWarnings,
                    "highlighting" => $highlighting
                ])
            @endforeach
        </div>
    @else
        @component("components.layout.infomessage", [
            "type" => InfoMessageType::WARNING
        ])
            {{ t("This user has not chosen any courses.") }}
        @endcomponent
    @endif

    <h3 class="mb-2">
        {{ t("Other courses") }}
    </h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-4">
        {{-- Unassign option --}}
        <button class="flex flex-col w-full p-4 gap-2 bg-opacity-20 rounded border border-2 text-left hover:scale-[1.025] transition-all bg-warning border-warning"
                data-course="">
            <span class="text-xl font-bold">
                {{ t("Unassign") }}
            </span>

            <span class="text-danger">
                {{ t("This will unassign the user from the course.") }}
            </span>
        </button>

        @foreach($otherCourses as $course)
            @include("assignment.components.edit.movecoursepreview", [
                "course" => $course,
                "courseWarnings" => $courseWarnings,
                "highlighting" => $highlighting
            ])
        @endforeach
    </div>

    <script type="module">
        import * as EditCourseAssignment from "{{ Router::staticFilePath("js/assignment/edit.js") }}";
        EditCourseAssignment.initMoveAwayModal("{{ Router::generate("course-assignment-edit-move-away", ["user" => $account->getId()]) }}");
    </script>
</div>
