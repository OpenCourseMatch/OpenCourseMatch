@component("shells.console", [
    "title" => t("Groups"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Groups") }}
    </h1>

    <div class="mb-4">
        @component("ui.box")
            {{ t("Customize user groups to model the participation requirements of the courses.") }}
        @endcomponent
    </div>

    <a id="create"
       href="{{ Router->generate("groups-create") }}"
       class="{{ TailwindUtil::button() }} gap-2">
        @include("icons.plus")
        {{ t("Create group") }}
    </a>

    <div class="overflow-x-auto">
        <table id="table" class="stripe">
            <thead>
                <tr>
                    <th>{{ t("Group name") }}</th>
                    <th>{{ t("Clearance level") }}</th>
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
            "{{ Router->generate("groups-overview-table") }}",
            {
                "name": {},
                "clearance": {}
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
