@component("components.layout.appshell")
    <h1 class="mb-2">
        {{ t("Edit course assignment") }}
    </h1>

    <div class="flex justify-between">
        <button class="{{ TailwindUtil::button() }} mb-2 gap-2">
            @include("components.icons.left")
            {{ t("Previous course") }}
        </button>

        <button class="{{ TailwindUtil::button() }} mb-2 gap-2">
            {{ t("Next course") }}
            @include("components.icons.right")
        </button>
    </div>
@endcomponent
