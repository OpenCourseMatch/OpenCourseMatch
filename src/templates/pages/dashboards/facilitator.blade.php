<h2 class="mt-4 mb-2">
    {{ t("Manage accounts") }}
</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    @include("ui.dashboardlink", [
        "icon" => "icons.user",
        "href" => Router->generate("users-overview"),
        "title" => t("Participants and tutors"),
        "description" => t("Manage accounts of participants and tutors."),
        "scheme" => BoxScheme::PRIMARY
    ])
</div>

<h2 class="mt-4 mb-2">
    {{ t("Manage courses") }}
</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    @include("ui.dashboardlink", [
        "icon" => "icons.course",
        "href" => Router->generate("courses-overview"),
        "title" => t("Courses"),
        "description" => t("Manage the available courses."),
        "scheme" => BoxScheme::PRIMARY
    ])
</div>

<h2 class="mt-4 mb-2">
    {{ t("About OpenCourseMatch") }}
</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    @include("ui.dashboardlink", [
        "icon" => "icons.bug",
        "href" => "https://github.com/OpenCourseMatch/OpenCourseMatch/issues/new/choose",
        "title" => t("Bug reports and feature requests"),
        "description" => t("Found a bug or have an idea to improve OpenCourseMatch? Please create an issue in our GitHub repository."),
        "scheme" => BoxScheme::PRIMARY,
        "external" => true
    ])
    {{-- Changelog --}}
</div>
