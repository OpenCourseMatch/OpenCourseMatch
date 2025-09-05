@component("shells.console", [
    "title" => t("Dashboard"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Dashboard") }}
    </h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        @include("ui.dashboardlink", [
            "icon" => "icons.accountsettings",
            "href" => Router->generate("account-settings"),
            "title" => t("Account settings"),
            "description" => t("Manage your personal information, security settings, and account preferences."),
            "scheme" => BoxScheme::PRIMARY
        ])
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
@endcomponent
