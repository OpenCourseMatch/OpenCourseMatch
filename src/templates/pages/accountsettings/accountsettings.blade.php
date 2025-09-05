@component("shells.console", [
    "title" => t("Account settings"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Account settings") }}
    </h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        @include("ui.dashboardlink", [
            "icon" => "icons.password",
            "href" => Router->generate("account-settings-change-password"),
            "title" => t("Change password"),
            "description" => t("Update your account password."),
            "scheme" => BoxScheme::PRIMARY
        ])
    </div>
@endcomponent
