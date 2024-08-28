@component("components.layout.appshell", [
    "title" => t("Groups"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Groups") }}
    </h1>

    <a id="create-group"
       href="{{ Router::generate("groups-create") }}"
       class="{{ TailwindUtil::button() }} gap-2">
        @include("components.icons.plus")
        {{ t("Create group") }}
    </a>

    <div class="overflow-x-auto">
        <table id="groups-table" class="stripe" data-table-ajax="{{ Router::generate("groups-overview-table") }}">
            <thead>
                <tr>
                    <th>{{ t("Group name") }}</th>
                    <th>{{ t("Clearance level") }}</th>
                </tr>
            </thead>
            <tbody>
                {{-- Contents filled by groups/overview.js --}}
            </tbody>
        </table>
    </div>

    <script type="module">
        import * as GroupsOverview from "{{ Router::staticFilePath("js/groups/overview.js") }}";
        GroupsOverview.init({
            "Search...": "{{ t("Search...") }}",
            "Loading...": "{{ t("Loading...") }}",
            "No entries": "{{ t("No entries") }}",
            "Back": "{{ t("Back") }}",
            "Next": "{{ t("Next") }}"
        });
    </script>
@endcomponent
