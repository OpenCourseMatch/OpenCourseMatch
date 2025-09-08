@component("shells.console", [
    "title" => t("Reset course assignment"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Reset course assignment") }}
    </h1>

    <p class="mb-2">
        {{ t("Do you really want to reset the course assignment?") }}
    </p>

    <form method="post" action="{{ Router->generate("course-assignment-reset") }}">
        <button type="submit" class="{{ TailwindUtil::button(false, "danger") }} gap-2">
            @include("icons.buttonload")
            @include("icons.reset")
            {{ t("Reset course assignment") }}
        </button>
    </form>
@endcomponent
