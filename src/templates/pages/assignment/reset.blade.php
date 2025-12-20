@component("shells.console", [
    "title" => t("Reset course assignment"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Reset course assignment") }}
    </h1>

    <div class="mb-4">
        @component("ui.box")
            {{ t("Reset the course assignment to re-run the assignment algorithm.") }}
        @endcomponent
    </div>

    <p class="mb-2">
        {{ t("Do you really want to reset the course assignment?") }}
    </p>

    <form method="post" action="{{ Router->generate("course-assignment-reset-action") }}">
        <button type="submit" class="{{ TailwindUtil::button(false, "danger") }} gap-2">
            @include("icons.buttonload")
            @include("icons.reset")
            {{ t("Reset course assignment") }}
        </button>
    </form>
@endcomponent
