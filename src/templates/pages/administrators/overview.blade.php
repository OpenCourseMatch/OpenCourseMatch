@component("shells.console", [
    "title" => t("Administrators"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Administrators") }}
    </h1>

    <div class="mb-4">
        @component("ui.box")
            {{ t("Manage accounts of administrators.") }}
        @endcomponent
    </div>

    <a id="create"
       href="{{ Router->generate("administrators-create") }}"
       class="{{ TailwindUtil::button() }} gap-2">
        @include("icons.plus")
        {{ t("Create administrator") }}
    </a>

    <div class="overflow-x-auto">
        <table id="table" class="stripe">
            <thead>
                <tr>
                    <th>{{ t("Username") }}</th>
                    <th>{{ t("First name") }}</th>
                    <th>{{ t("Last name") }}</th>
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
            "{{ Router->generate("administrators-overview-table") }}",
            {
                "username": {},
                "firstName": {},
                "lastName": {}
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
