<h2 class="mt-4 mb-2">
    {{ t("Statistics") }}
</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    <div class="flex flex-col w-full justify-center p-4 gap-2 bg-opacity-20 rounded border border-2 bg-primary border-primary">
        <p class="text-xl font-bold">
            {{ t("System status") }}
        </p>

        <div class="flex gap-2">
            @if(SystemStatus::dao()->get("userActionsAllowed") === "true")
                @include("components.icons.checkcircle", [
                    "class" => "fill-safe"
                ])
            @else
                @include("components.icons.crosscircle", [
                    "class" => "fill-danger"
                ])
            @endif
            <p>
                {{ t("User login") }}
            </p>
        </div>

        <div class="flex gap-2">
            @if(SystemStatus::dao()->get("coursesAssigned") === "true")
                @include("components.icons.checkcircle", [
                    "class" => "fill-safe"
                ])
            @else
                @include("components.icons.crosscircle", [
                    "class" => "fill-danger"
                ])
            @endif
            <p>
                {{ t("Courses assigned") }}
            </p>
        </div>
    </div>

    <div class="flex flex-col w-full justify-center items-center p-4 gap-2 bg-opacity-20 rounded border border-2 bg-primary border-primary">
        <p class="text-4xl font-bold">
            {{ $numberOfParticipantsAndTutors ? number_format($numberOfParticipantsAndTutors, 0, ",", ".") : 0 }}
        </p>
        <p>
            {{ t("Participants and tutors") }}
        </p>
    </div>

    <div class="flex flex-col w-full justify-center items-center p-4 gap-2 bg-opacity-20 rounded border border-2 bg-primary border-primary">
        <p class="text-4xl font-bold">
            {{ $numberOfCourses ? number_format($numberOfCourses, 0, ",", ".") : 0 }}
        </p>
        <p>
            {{ t("Courses") }}
        </p>
    </div>

    {{-- View more statistics --}}
</div>

<h2 class="mt-4 mb-2">
    {{ t("Manage accounts") }}
</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    @include("components.dashboardlink", [
        "icon" => "components.icons.group",
        "href" => Router::generate("groups-overview"),
        "title" => t("Groups"),
        "description" => t("Customize user groups to model the participation requirements of the courses.")
    ])
    @include("components.dashboardlink", [
        "icon" => "components.icons.user",
        "href" => Router::generate("users-overview"),
        "title" => t("Participants and tutors"),
        "description" => t("Manage accounts of participants and tutors.")
    ])
    @include("components.dashboardlink", [
        "icon" => "components.icons.user",
        "href" => Router::generate("facilitators-overview"),
        "title" => t("Facilitators"),
        "description" => t("Manage accounts of facilitators.")
    ])
    @include("components.dashboardlink", [
        "icon" => "components.icons.user",
        "href" => Router::generate("administrators-overview"),
        "title" => t("Administrators"),
        "description" => t("Manage accounts of administrators.")
    ])
</div>

<h2 class="mt-4 mb-2">
    {{ t("Manage courses") }}
</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    @include("components.dashboardlink", [
        "icon" => "components.icons.course",
        "href" => Router::generate("courses-overview"),
        "title" => t("Courses"),
        "description" => t("Manage the available courses.")
    ])
</div>

@if(SystemStatus::dao()->get("coursesAssigned") !== "true" && SystemStatus::dao()->get("algorithmRunning") !== "true")
    <h2 class="mt-4 mb-2">
        {{ t("Course assignment") }}
    </h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        @include("components.dashboardlink", [
            "icon" => "components.icons.algorithm",
            "href" => Router::generate("course-assignment-run"),
            "title" => t("Run course assignment"),
            "description" => t("Start the assignment algorithm to group participants to the courses based on their preferences.")
        ])
    </div>
@elseif(SystemStatus::dao()->get("coursesAssigned") === "true")
    <h2 class="mt-4 mb-2">
        {{ t("Course assignment") }}
    </h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        {{-- Print course assignment --}}
        @include("components.dashboardlink", [
            "icon" => "components.icons.assignment",
            "href" => Router::generate("course-assignment-edit"),
            "title" => t("Edit course assignment"),
            "description" => t("Optimize the course assignment manually.")
        ])
        @include("components.dashboardlink", [
            "icon" => "components.icons.reset",
            "href" => Router::generate("course-assignment-reset"),
            "title" => t("Reset course assignment"),
            "description" => t("Reset the course assignment to re-run the assignment algorithm."),
            "danger" => true
        ])
    </div>
@endif

<h2 class="mt-4 mb-2">
    {{ t("Settings") }}
</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    @include("components.dashboardlink", [
        "icon" => "components.icons.gear",
        "href" => Router::generate("system-settings"),
        "title" => t("System settings"),
        "description" => t("Configure OpenCourseMatch to your organizations' needs.")
    ])
    @include("components.dashboardlink", [
        "icon" => "components.icons.user",
        "href" => Router::generate("user-actions-toggle"),
        "title" => SystemStatus::dao()->get("userActionsAllowed") === "true" ? t("Course selection enabled") : t("Course selection disabled"),
        "description" => SystemStatus::dao()->get("userActionsAllowed") === "true" ? t("Disable the course selection for users.") : t("Enable the course selection for users."),
        "danger" => SystemStatus::dao()->get("userActionsAllowed") !== "true"
    ])
    {{-- Reset all data --}}
</div>

<h2 class="mt-4 mb-2">
    {{ t("About OpenCourseMatch") }}
</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    @include("components.dashboardlink", [
        "icon" => "components.icons.bug",
        "href" => "https://github.com/OpenCourseMatch/OpenCourseMatch/issues/new/choose",
        "title" => t("Bug reports and feature requests"),
        "description" => t("Found a bug or have an idea to improve OpenCourseMatch? Please create an issue in our GitHub repository."),
        "external" => true
    ])
    {{-- Changelog --}}
</div>
