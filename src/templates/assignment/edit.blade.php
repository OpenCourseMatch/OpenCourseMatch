@component("components.layout.appshell", [
    "title" => t("Edit course assignment"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Edit course assignment") }}
    </h1>

    <div class="flex justify-between gap-2 mb-2">
        <button class="{{ TailwindUtil::button() }} mb-2 gap-2">
            @include("components.icons.left")
            {{ t("Previous course") }}
        </button>

        <button class="{{ TailwindUtil::button() }} mb-2 gap-2">
            {{ t("Next course") }}
            @include("components.icons.right")
        </button>
    </div>

    <div id="courseoverview"></div>

    <script type="module">
        // TODO: Load course overview from ajax
    </script>
@endcomponent
