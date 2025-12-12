@component("shells.console", [
    "title" => t("Facilitators"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Facilitators") }}
    </h1>

    <a id="create"
       href="{{ Router->generate("facilitators-create") }}"
       class="{{ TailwindUtil::button() }} gap-2">
        @include("icons.plus")
        {{ t("Create facilitator") }}
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
            "{{ Router->generate("facilitators-overview-table") }}",
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
