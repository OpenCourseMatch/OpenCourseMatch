@component("shells.console", [
    "title" => t("Participants and tutors"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Participants and tutors") }}
    </h1>

    <div class="mb-4">
        @component("ui.box")
            {{ t("Manage accounts of participants and tutors.") }}
        @endcomponent
    </div>

    <div id="table-actions" class="flex flex-col sm:flex-row gap-2">
        <a id="create"
           href="{{ Router->generate("users-create") }}"
           class="{{ TailwindUtil::button() }} gap-2">
            @include("icons.plus")
            {{ t("Create user") }}
        </a>

        <a id="import-users"
           href="{{ Router->generate("users-import") }}"
           class="{{ TailwindUtil::button() }} gap-2">
            @include("icons.import")
            {{ t("Import users") }}
        </a>

        <a id="group-actions"
           href="{{ Router->generate("group-actions") }}"
           class="{{ TailwindUtil::button() }} gap-2">
            @include("icons.group")
            {{ t("Group actions") }}
        </a>
    </div>

    <div class="overflow-x-auto">
        <table id="table" class="stripe">
            <thead>
                <tr>
                    <th>{{ t("Username") }}</th>
                    <th>{{ t("First name") }}</th>
                    <th>{{ t("Last name") }}</th>
                    <th>{{ t("Group") }}</th>
                    <th>{{ t("Choice complete") }}</th>
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
            "{{ Router->generate("users-overview-table") }}",
            {
                "username": {},
                "firstName": {},
                "lastName": {},
                "group": {},
                "choiceComplete": {
                    render: TableOverview.renderBoolean
                }
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
