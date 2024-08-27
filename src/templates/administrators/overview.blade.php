@component("components.layout.appshell", [
    "title" => t("Administrators"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Administrators") }}
    </h1>

    <a id="create-user"
       href="{{ Router::generate("administrators-create") }}"
       class="{{ TailwindUtil::button() }} gap-2">
        @include("components.icons.plus")
        {{ t("Create facilitator") }}
    </a>

    <div class="overflow-x-auto">
        <table id="users-table" class="stripe" data-table-ajax="{{ Router::generate("administrators-overview-table") }}">
            <thead>
                <tr>
                    <th>{{ t("Username") }}</th>
                    <th>{{ t("First name") }}</th>
                    <th>{{ t("Last name") }}</th>
                </tr>
            </thead>
            <tbody>
                {{-- Contents filled by administrators/overview.js --}}
            </tbody>
        </table>
    </div>

    <script type="module">
        import AdministratorsOverview from "{{ Router::staticFilePath("js/administrators/overview.js") }}";
        AdministratorsOverview.init({
            "Search...": "{{ t("Search...") }}",
            "Loading...": "{{ t("Loading...") }}",
            "No entries": "{{ t("No entries") }}",
            "Back": "{{ t("Back") }}",
            "Next": "{{ t("Next") }}"
        });
    </script>
@endcomponent
