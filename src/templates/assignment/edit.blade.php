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

    <div data-courseoverview="">
        <div class="flex flex-col sm:flex-row items-center gap-x-4 gap-y-2 flex-wrap">
            <h2>Course title</h2>
            <div class="flex flex-wrap gap-2">
                <div class="flex flex-row whitespace-nowrap">
                    <span class="pl-2 pr-1 text-primary-font bg-primary rounded-l-full border border-primary">
                        Participants
                    </span>
                    <span class="pl-1 pr-2 rounded-r-full border border-primary">
                        0 / 0 / 0
                    </span>
                </div>
                <div class="flex flex-row whitespace-nowrap">
                    <span class="pl-2 pr-1 text-primary-font bg-primary rounded-l-full border border-primary">
                        Clearance level
                    </span>
                    <span class="pl-1 pr-2 rounded-r-full border border-primary">
                        0 - 0
                    </span>
                </div>
            </div>
        </div>
        <!-- TODO: Error messages -->
    </div>
@endcomponent
