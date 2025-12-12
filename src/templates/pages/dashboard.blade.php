@component("shells.console", [
    "title" => t("Dashboard"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Dashboard") }}
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @component("ui.box", [
            "scheme" => BoxScheme::SURFACE
        ])
            <div>
                <p>
                    {{ t("Welcome") }},
                </p>
                <p class="text-lg font-bold">
                    {{ Auth->getLoggedInUser()->getFullName() }}!
                </p>
            </div>
        @endcomponent
    </div>

    @auth(0)
        @include("pages.dashboards.user")
    @endauth
    @auth(1)
        @include("pages.dashboards.facilitator")
    @endauth
    @auth(2)
        @include("pages.dashboards.admin")
    @endauth

    <h2 class="mt-4 mb-2">
        {{ t("About OpenCourseMatch") }}
    </h2>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @include("ui.dashboardlink", [
            "icon" => "icons.dependencies",
            "href" => Router->generate("dependencies-overview"),
            "title" => t("Dependencies"),
            "description" => t("OpenCourseMatch is built using these open-source packages."),
            "scheme" => BoxScheme::SURFACE
        ])

        @include("ui.dashboardlink", [
            "icon" => "icons.bug",
            "href" => "https://github.com/OpenCourseMatch/OpenCourseMatch/issues/new/choose",
            "title" => t("Bug reports and feature requests"),
            "description" => t("Found a bug or have an idea to improve OpenCourseMatch? Please create an issue in our GitHub repository."),
            "scheme" => BoxScheme::SURFACE,
            "external" => true
        ])
    </div>
@endcomponent
