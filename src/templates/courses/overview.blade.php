@component("components.layout.appshell", [
    "title" => t("Courses"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Courses") }}
    </h1>

    <a id="create-course"
       href="{{ Router::generate("courses-create") }}"
       class="{{ TailwindUtil::button() }} gap-2">
        @component("components.icons.plus")@endcomponent
        {{ t("Create course") }}
    </a>

    <div class="overflow-x-auto">
        <table id="courses-table" class="stripe" data-table-ajax="{{ Router::generate("courses-overview-table") }}">
            <thead>
                <tr>
                    <th>{{ t("Title") }}</th>
                </tr>
            </thead>
            <tbody>
                {{-- Contents filled by courses/overview.js --}}
            </tbody>
        </table>
    </div>

    <script type="module">
        import * as CoursesOverview from "{{ Router::staticFilePath("js/courses/overview.js") }}";
        CoursesOverview.init({
            "Search...": "{{ t("Search...") }}",
            "Loading...": "{{ t("Loading...") }}",
            "No entries": "{{ t("No entries") }}",
            "Back": "{{ t("Back") }}",
            "Next": "{{ t("Next") }}"
        });
    </script>
@endcomponent
