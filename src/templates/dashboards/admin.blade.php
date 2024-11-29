<h2 class="mt-4 mb-2">
    {{ t("Statistics") }}
</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    {{-- Number of participants and tutors --}}
    {{-- Number of courses --}}
    {{-- Login for participants and tutors allowed --}}
    {{-- Courses assigned --}}
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

<h2 class="mt-4 mb-2">
    {{ t("Course assignment") }}
</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    @if(SystemStatus::dao()->get("coursesAssigned") !== "true" && SystemStatus::dao()->get("algorithmRunning") !== "true")
        @include("components.dashboardlink", [
            "icon" => "components.icons.algorithm",
            "href" => Router::generate("course-assignment-run"),
            "title" => t("Run course assignment"),
            "description" => t("Start the assignment algorithm to group participants to the courses based on their preferences.")
        ])
    @endif
    {{-- Edit course assignment --}}
    {{-- Print course assignment --}}
    {{-- Reset course assignment --}}
</div>

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
