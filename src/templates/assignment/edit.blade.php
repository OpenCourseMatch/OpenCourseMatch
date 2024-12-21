@component("components.layout.appshell", [
    "title" => t("Edit course assignment"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Edit course assignment") }}
    </h1>

    <div class="flex justify-between gap-2 mb-2">
        <button class="{{ TailwindUtil::button() }} mb-2 gap-2" id="previous-course">
            @include("components.icons.left")
            {{ t("Previous course") }}
        </button>

        <button class="{{ TailwindUtil::button() }} mb-2 gap-2" id="next-course">
            {{ t("Next course") }}
            @include("components.icons.right")
        </button>
    </div>

    <div id="courseoverview"></div>

    <div id="loadanimation" class="flex justify-around w-full">
        @include("components.icons.loading")
    </div>

    <div id="loaderror" class="hidden">
        @component("components.layout.infomessage", [
            "type" => InfoMessageType::ERROR
        ])
            {{ t("An error has occurred whilst loading the course overview. Please try again later.") }}
        @endcomponent
    </div>

    <script type="module">
        import * as EditCourseAssignment from "{{ Router::staticFilePath("js/assignment/edit.js") }}";
        EditCourseAssignment.init(@json($courseIds), "{{ Router::generate("course-assignment-edit-courseoverview") }}");
    </script>
@endcomponent
