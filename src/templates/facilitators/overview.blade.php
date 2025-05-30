@component("components.layout.appshell", [
    "title" => t("Facilitators"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Facilitators") }}
    </h1>

    <a id="create-user"
       href="{{ Router::generate("facilitators-create") }}"
       class="{{ TailwindUtil::button() }} gap-2">
        @include("components.icons.plus")
        {{ t("Create facilitator") }}
    </a>

    <div class="overflow-x-auto">
        <table id="users-table" class="stripe" data-table-ajax="{{ Router::generate("facilitators-overview-table") }}">
            <thead>
                <tr>
                    <th>{{ t("Username") }}</th>
                    <th>{{ t("First name") }}</th>
                    <th>{{ t("Last name") }}</th>
                </tr>
            </thead>
            <tbody>
                {{-- Contents filled by facilitators/overview.js --}}
            </tbody>
        </table>
    </div>

    <script type="module">
        import * as FacilitatorsOverview from "{{ Router::staticFilePath("js/facilitators/overview.js") }}";
        FacilitatorsOverview.init({
            "Search...": "{{ t("Search...") }}",
            "Loading...": "{{ t("Loading...") }}",
            "No entries": "{{ t("No entries") }}",
            "Back": "{{ t("Back") }}",
            "Next": "{{ t("Next") }}"
        });
    </script>
@endcomponent
