@component("components.layout.appshell", [
    "title" => t("Reset system data"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Reset system data") }}
    </h1>

    <form method="post" action="{{ Router::generate("system-reset-action") }}">
        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <input id="resetCourses"
                   name="resetCourses"
                   type="checkbox"
                   value="1"
                   class="{{ TailwindUtil::$checkbox }}">
            <label for="resetCourses" class="{{ TailwindUtil::$inputLabel }}">
                {{ t("Reset courses") }}
            </label>
        </div>

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <input id="resetUsers"
                   name="resetUsers"
                   type="checkbox"
                   value="1"
                   class="{{ TailwindUtil::$checkbox }}">
            <label for="resetUsers" class="{{ TailwindUtil::$inputLabel }}">
                {{ t("Reset user accounts") }}
            </label>
        </div>

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <input id="resetFacilitators"
                   name="resetFacilitators"
                   type="checkbox"
                   value="1"
                   class="{{ TailwindUtil::$checkbox }}">
            <label for="resetFacilitators" class="{{ TailwindUtil::$inputLabel }}">
                {{ t("Reset facilitator accounts") }}
            </label>
        </div>

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <input id="resetGroups"
                   name="resetGroups"
                   type="checkbox"
                   value="1"
                   class="{{ TailwindUtil::$checkbox }}">
            <label for="resetGroups" class="{{ TailwindUtil::$inputLabel }}">
                {{ t("Reset groups") }}
            </label>
        </div>

        <button type="submit" class="{{ TailwindUtil::button(false, "danger") }} gap-2">
            @include("components.icons.buttonload")
            @include("components.icons.reset")
            {{ t("Reset selected system data") }}
        </button>
    </form>
@endcomponent
