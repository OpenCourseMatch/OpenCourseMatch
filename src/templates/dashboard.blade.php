@component("components.layout.appshell", [
    "title" => t("Dashboard"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Dashboard") }}
    </h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        @component("components.dashboardlink", [
            "icon" => "components.icons.accountsettings",
            "href" => Router::generate("account-settings"),
            "title" => t("Account settings"),
            "description" => t("Manage your personal information, security settings, and account preferences.")
        ])@endcomponent
    </div>

    @auth(0)
        @component("dashboards.user")@endcomponent
    @endauth
    @auth(1)
        @component("dashboards.facilitator")@endcomponent
    @endauth
    @auth(2)
        @component("dashboards.admin")@endcomponent
    @endauth
@endcomponent
