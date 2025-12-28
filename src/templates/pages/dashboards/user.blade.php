@if(\app\settings\SystemStatus::dao()->get("userActionsAllowed") === "true")
    <h2 class="mt-4 mb-2">
        {{ t("Choose courses") }}
    </h2>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @include("ui.dashboardlink", [
            "icon" => "icons.course",
            "href" => Router->generate("choice-edit"),
            "title" => t("Choose courses"),
            "description" => t("Rank your favourite courses that you would like to participate in."),
            "scheme" => BoxScheme::SURFACE
        ])
    </div>
@endif

@if(\app\settings\SystemStatus::dao()->get("courseAssignmentPublic") === "true")
    <h2 class="mt-4 mb-2">
        {{ t("Your assigned course") }}
    </h2>

    @if($leadingCourseWasCancelled)
        @component("shells.generic.infomessage", [
            "type" => \struktal\InfoMessage\InfoMessageType::ERROR
        ])
            {{ t("The course that you are leading has been cancelled.") }}
        @endcomponent
    @endif

    @if(!$assignedCourse instanceof \app\courses\Course)
        @component("shells.generic.infomessage", [
            "type" => \struktal\InfoMessage\InfoMessageType::ERROR
        ])
            {{ t("You could not be assigned to any course. Please contact an administrator.") }}
        @endcomponent
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            @include("ui.choice", [
                "course" => $assignedCourse,
                "choice" => -1
            ])
        </div>
    @endif
@endif

@if(
    \app\settings\SystemStatus::dao()->get("userActionsAllowed") !== "true" &&
    \app\settings\SystemStatus::dao()->get("courseAssignmentPublic") !== "true"
)
    <h2 class="mt-4 mb-2">
        {{ t("Choose courses") }}
    </h2>

    @component("shells.generic.infomessage", [
        "type" => \struktal\InfoMessage\InfoMessageType::WARNING
    ])
        {{ t("The course selection has already been disabled. You can no longer update your course preferences.") }}
    @endcomponent
@endif
