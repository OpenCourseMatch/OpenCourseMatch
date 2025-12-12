@component("shells.console", [
    "title" => t("Account settings"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Account settings") }}
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        @include("ui.dashboardlink", [
            "icon" => "icons.password",
            "href" => Router->generate("account-settings-change-password"),
            "title" => t("Change password"),
            "description" => t("Update your account password."),
            "scheme" => BoxScheme::SURFACE
        ])
    </div>
@endcomponent
