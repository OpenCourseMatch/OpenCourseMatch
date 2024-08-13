@component("components.layout.appshell", ["title" => t("Groups")])
    <h1 class="mb-2">
        {{ t("Groups") }}
    </h1>

    <a id="create-group"
       href="{{ Router::generate("groups-create") }}"
       class="{{ TailwindUtil::button() }}">
        {{ t("Create group") }}
    </a>

    <div class="overflow-x-auto">
        <table id="groups-table" class="stripe">
            <thead>
                <tr>
                    <th>{{ t("Group name") }}</th>
                    <th>{{ t("Clearance level") }}</th>
                </tr>
            </thead>
            <tbody>
                {{-- TODO --}}
            </tbody>
        </table>
    </div>

    <script type="module">
        import GroupOverview from "{{ Router::staticFilePath("js/groups/overview.js") }}";
        GroupOverview.init();
    </script>
@endcomponent
