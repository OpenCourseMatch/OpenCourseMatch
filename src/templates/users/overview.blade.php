@component("components.layout.appshell", [
    "title" => t("Participants and tutors"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Participants and tutors") }}
    </h1>

    <div id="table-actions" class="flex flex-col sm:flex-row gap-2">
        <a id="create-user"
           href="{{ Router::generate("users-create") }}"
           class="{{ TailwindUtil::button() }} gap-2">
            @component("components.icons.plus")@endcomponent
            {{ t("Create user") }}
        </a>

        <a id="import-users"
           href="{{ Router::generate("users-import") }}"
           class="{{ TailwindUtil::button() }} gap-2">
            @component("components.icons.import")@endcomponent
            {{ t("Import users") }}
        </a>

        <a id="group-actions"
           href="{{ Router::generate("group-actions") }}"
           class="{{ TailwindUtil::button() }} gap-2">
            @component("components.icons.group")@endcomponent
            {{ t("Group actions") }}
        </a>
    </div>

    <div class="overflow-x-auto">
        <table id="users-table" class="stripe" data-table-ajax="{{ Router::generate("users-overview-table") }}">
            <thead>
                <tr>
                    <th>{{ t("Username") }}</th>
                    <th>{{ t("First name") }}</th>
                    <th>{{ t("Last name") }}</th>
                    <th>{{ t("Group") }}</th>
                </tr>
            </thead>
            <tbody>
                {{-- Contents filled by users/overview.js --}}
            </tbody>
        </table>
    </div>

    <script type="module">
        import * as UsersOverview from "{{ Router::staticFilePath("js/users/overview.js") }}";
        UsersOverview.init({
            "Search...": "{{ t("Search...") }}",
            "Loading...": "{{ t("Loading...") }}",
            "No entries": "{{ t("No entries") }}",
            "Back": "{{ t("Back") }}",
            "Next": "{{ t("Next") }}"
        });
    </script>
@endcomponent
