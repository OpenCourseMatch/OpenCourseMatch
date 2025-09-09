@component("shells.console", [
    "title" => $userActionsAllowed ? t("Disable course selection") : t("Enable course selection"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ $userActionsAllowed ? t("Disable course selection") : t("Enable course selection") }}
    </h1>

    <p class="mb-2">
        {{ $userActionsAllowed ? t("Disable the course selection for users.") : t("Enable the course selection for users.") }}
    </p>

    <form method="post" action="{{ Router->generate("choice-state-toggle-action") }}">
        <input type="hidden" name="userActionsAllowed" value="{{ $userActionsAllowed ? "0" : "1" }}">
        <button type="submit" class="{{ TailwindUtil::button(false, "danger") }} gap-2">
            @include("icons.buttonload")
            @include("icons.user")
            {{ $userActionsAllowed ? t("Disable course selection") : t("Enable course selection") }}
        </button>
    </form>
@endcomponent
