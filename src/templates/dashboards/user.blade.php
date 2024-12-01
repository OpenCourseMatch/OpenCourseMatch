<h2 class="mt-4 mb-2">
    {{ t("Choose courses") }}
</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    @include("components.dashboardlink", [
        "icon" => "components.icons.course",
        "href" => Router::generate("choice-edit"),
        "title" => t("Choose courses"),
        "description" => t("Rank your favourite courses that you would like to participate in."),
        "danger" => false
    ])
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
        "external" => true,
        "danger" => false
    ])
    {{-- Changelog --}}
</div>
