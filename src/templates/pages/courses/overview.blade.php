@component("shells.console", [
    "title" => t("Courses"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Courses") }}
    </h1>

    <div class="mb-4">
        @component("ui.helpbox")
            {{ t("Manage the available courses.") }}
        @endcomponent
    </div>

    <a id="create"
       href="{{ Router->generate("courses-create") }}"
       class="{{ TailwindUtil::button() }} gap-2">
        @include("icons.plus")
        {{ t("Create course") }}
    </a>

    <div class="overflow-x-auto">
        <table id="table" class="stripe">
            <thead>
                <tr>
                    <th>{{ t("Title") }}</th>
                </tr>
            </thead>
            <tbody>
                {{-- Contents filled by TableOverview.js --}}
            </tbody>
        </table>
    </div>

    <script type="module">
        import * as TableOverview from "{{ Router->staticFilePath("js/TableOverview.js") }}";
        TableOverview.init(
            "table",
            "{{ Router->generate("courses-overview-table") }}",
            {
                "title": {}
            },
            {
                "Search...": "{{ t("Search...") }}",
                "Loading...": "{{ t("Loading...") }}",
                "No entries": "{{ t("No entries") }}",
                "Back": "{{ t("Back") }}",
                "Next": "{{ t("Next") }}"
            }
        );
    </script>
@endcomponent
