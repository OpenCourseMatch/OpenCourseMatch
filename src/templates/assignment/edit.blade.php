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

    <dialog id="moveaway-modal"
            class="hidden flex flex-col p-0 w-[90vw] max-w-[960px] bg-gray-light border-none rounded text-font">
        <div class="flex items-center justify-between w-full gap-4 p-4 border-b border-b-gray">
            <h2 class="m-0">
                {{ t("Move user") }}
            </h2>
            <div class="">
                <button class="{{ TailwindUtil::button(false, "gray") }} moveaway-modal-abort-button">
                    {{ t("Abort") }}
                </button>
            </div>
        </div>
        <div class="grow w-full p-4 overflow-y-auto">
            <div class="flex justify-center items-center h-full" id="moveaway-modal-loading">
                @include("components.icons.loading")
            </div>
            <div class="hidden" id="moveaway-modal-content-body">
                {{-- Filled by assignment/edit.js --}}
            </div>
        </div>
        <div class="flex items-center justify-end w-full gap-4 p-4 border-t border-t-gray">
            <div class="">
                <button class="{{ TailwindUtil::button(false, "gray") }} moveaway-modal-abort-button">
                    {{ t("Abort") }}
                </button>
            </div>
        </div>
    </dialog>

    <script type="module">
        import * as EditCourseAssignment from "{{ Router::staticFilePath("js/assignment/edit.js") }}";
        EditCourseAssignment.init(@json($courseIds), "{{ Router::generate("course-assignment-edit-courseoverview") }}");
    </script>
@endcomponent
