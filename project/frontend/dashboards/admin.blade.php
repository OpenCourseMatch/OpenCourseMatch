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
    {{-- Participants and tutors --}}
    {{-- Helpers --}}
    {{-- Administrators --}}
</div>

<h2 class="mt-4 mb-2">
    {{ t("Manage courses") }}
</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    {{-- Courses --}}
</div>

<h2 class="mt-4 mb-2">
    {{ t("Course assignment") }}
</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    {{-- Assign courses to participants --}}
    {{-- Edit course assignment --}}
    {{-- Print course assignment --}}
    {{-- Reset course assignment --}}
</div>

<h2 class="mt-4 mb-2">
    {{ t("Settings") }}
</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    {{-- General settings --}}
    {{-- Reset all data --}}
</div>

<h2 class="mt-4 mb-2">
    {{ t("About OpenCourseMatch") }}
</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    {{-- Changelog --}}
</div>
