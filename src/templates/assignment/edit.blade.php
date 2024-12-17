@component("components.layout.appshell", [
    "title" => t("Edit course assignment"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Edit course assignment") }}
    </h1>

    <div class="flex justify-between mb-2">
        <button class="{{ TailwindUtil::button() }} mb-2 gap-2">
            @include("components.icons.left")
            {{ t("Previous course") }}
        </button>

        <button class="{{ TailwindUtil::button() }} mb-2 gap-2">
            {{ t("Next course") }}
            @include("components.icons.right")
        </button>
    </div>

    <div data-courseoverview="">
        <div class="flex flex-col md:flex-row">
            <h2>Course title</h2>
            <span class="">
                0 / 0 / 0 <!--Min / Current / Max -->
            </span>
            <span class="">
                0 - 0 <!-- Clearance level -->
            </span>
        </div>
        <!-- TODO: Error messages -->
    </div>
@endcomponent
