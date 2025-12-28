@component("shells.console", [
    "title" => $courseAssignmentPublic ? t("Hide course assignment") : t("Publish course assignment"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ $courseAssignmentPublic ? t("Hide course assignment") : t("Publish course assignment") }}
    </h1>

    <p class="mb-2">
        {{ $courseAssignmentPublic ? t("Don't show the users to which course they have been assigned.") : t("Show the users to which course they have been assigned.") }}
    </p>

    <form method="post" action="{{ Router->generate("assignment-public-state-toggle-action") }}">
        <input type="hidden" name="courseAssignmentPublic" value="{{ $courseAssignmentPublic ? "0" : "1" }}">
        <button type="submit" class="{{ TailwindUtil::button(false, "danger") }} gap-2">
            @include("icons.buttonload")
            @include("icons.public")
            {{ $courseAssignmentPublic ? t("Hide course assignment") : t("Publish course assignment") }}
        </button>
    </form>
@endcomponent
