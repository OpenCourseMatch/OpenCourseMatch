@component("components.layout.appshell", [
    "title" => t("Courses"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        @if(!empty($course))
            {{ t("Edit course \$\$name\$\$", ["name" => $course->getTitle()]) }}
        @else
            {{ t("Create course") }}
        @endif
    </h1>

    <form method="post" action="{{ Router::generate("courses-save") }}">
        @if(!empty($course))
            <input type="hidden" name="course" value="{{ $course->getId() }}">
        @endif

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label for="title" class="{{ TailwindUtil::$inputLabel }}" data-required>
                {{ t("Title") }}
            </label>
            <input id="title"
                   name="title"
                   type="text"
                   class="{{ TailwindUtil::$input }}"
                   value="{{ !empty($course) ? $course->getTitle() : "" }}"
                   placeholder="{{ t("Title") }}"
                   maxlength="256"
                   required>
        </div>

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label for="organizer" class="{{ TailwindUtil::$inputLabel }}">
                {{ t("Organizer") }}
            </label>
            <input id="organizer"
                   name="organizer"
                   type="text"
                   class="{{ TailwindUtil::$input }}"
                   value="{{ !empty($course) ? $course->getOrganizer() ?? "" : "" }}"
                   maxlength="256"
                   placeholder="{{ t("Organizer") }}">
        </div>

        <div class="flex flex-col md:flex-row gap-2 mb-2">
            <div class="{{ TailwindUtil::inputGroup() }}">
                <label for="minClearance" class="{{ TailwindUtil::$inputLabel }}" data-required>
                    {{ t("Minimum required clearance") }}
                </label>
                <input id="minClearance"
                       name="minClearance"
                       type="number"
                       class="{{ TailwindUtil::$input }}"
                       value="{{ !empty($course) ? $course->getMinClearance() : "" }}"
                       placeholder="{{ t("Minimum required clearance") }}"
                       required>
            </div>

            <div class="{{ TailwindUtil::inputGroup() }}">
                <label for="maxClearance" class="{{ TailwindUtil::$inputLabel }}">
                    {{ t("Maximum allowed clearance") }}
                </label>
                <input id="maxClearance"
                       name="maxClearance"
                       type="number"
                       class="{{ TailwindUtil::$input }}"
                       value="{{ !empty($course) ? $course->getMaxClearance() ?? "" : "" }}"
                       placeholder="{{ t("Maximum allowed clearance") }}">
            </div>
        </div>

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label for="maxParticipants" class="{{ TailwindUtil::$inputLabel }}" data-required>
                {{ t("Maximum participants") }}
            </label>
            <input id="maxParticipants"
                   name="maxParticipants"
                   type="number"
                   min="1"
                   class="{{ TailwindUtil::$input }}"
                   value="{{ !empty($course) ? $course->getMaxParticipants() : "" }}"
                   placeholder="{{ t("Maximum participants") }}"
                   required>
        </div>

        <button type="submit" class="{{ TailwindUtil::button() }} gap-2">
            @include("components.icons.buttonload")
            @include("components.icons.save")
            {{ t("Save") }}
        </button>

        @if(!empty($course))
            <button type="button"
                    id="delete-course"
                    class="{{ TailwindUtil::button(false, "danger") }} gap-2"
                    data-delete-href="{{ Router::generate("courses-delete", ["course" => $course->getId()]) }}">
                @include("components.icons.buttonload")
                @include("components.icons.delete")
                {{ t("Delete") }}
            </button>
        @endif
    </form>

    @include("components.modals.defaultabort")
    <script type="module">
        import * as CoursesEdit from "{{ Router::staticFilePath("js/courses/edit.js") }}";
        CoursesEdit.init();
    </script>
@endcomponent
