@component("components.layout.appshell", [
    "title" => t("Dashboard"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Dashboard") }}
    </h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        @include("components.dashboardlink", [
            "icon" => "components.icons.accountsettings",
            "href" => Router::generate("account-settings"),
            "title" => t("Account settings"),
            "description" => t("Manage your personal information, security settings, and account preferences.")
        ])
    </div>

    @auth(0)
        @include("dashboards.user")
    @endauth
    @auth(1)
        @include("dashboards.facilitator")
    @endauth
    @auth(2)
        @include("dashboards.admin")
    @endauth
@endcomponent
