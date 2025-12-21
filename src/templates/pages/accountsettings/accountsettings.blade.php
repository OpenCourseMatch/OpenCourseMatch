@component("shells.console", [
    "title" => t("Account settings"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Account settings") }}
    </h1>

    <div class="mb-4">
        @component("ui.helpbox")
            {{ t("Manage your personal information, security settings, and account preferences.") }}
        @endcomponent
    </div>

    <form method="post" action="{{ Router->generate("account-settings-save") }}">
        <h2 class="mt-4 mb-2">
            {{ t("Preferences") }}
        </h2>

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <input id="showHelpBoxes"
                   name="showHelpBoxes"
                   type="checkbox"
                   value="1"
                   class="{{ TailwindUtil::$checkbox }}"
                   @if($user->getShowHelpBoxes()) checked @endif>
            <label for="showHelpBoxes" class="{{ TailwindUtil::$inputLabel }}">
                {{ t("Show detailed information boxes") }}
            </label>
        </div>

        <button type="submit" class="{{ TailwindUtil::button() }} gap-2">
            @include("icons.buttonload")
            @include("icons.save")
            {{ t("Save settings") }}
        </button>
    </form>

    <h2 class="mt-4 mb-2">
        {{ t("Other settings") }}
    </h2>

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
