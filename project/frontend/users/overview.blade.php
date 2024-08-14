@component("components.layout.appshell", ["title" => t("Participants and tutors")])
    <h1 class="mb-2">
        {{ t("Participants and tutors") }}
    </h1>

    <a id="create-user"
       href="{{ Router::generate("users-create") }}"
       class="{{ TailwindUtil::button() }} gap-2">
        @include("components.icons.plus")
        {{ t("Create user") }}
    </a>

    <div class="overflow-x-auto">
        <table id="users-table" class="stripe" data-table-ajax="{{ Router::generate("users-overview-table") }}">
            <thead>
                <tr>
                    <th>{{ t("First name") }}</th>
                    <th>{{ t("Last name") }}</th>
                </tr>
            </thead>
            <tbody>
                {{-- Contents filled by users/overview.js --}}
            </tbody>
        </table>
    </div>

    <script type="module">
        import UserOverview from "{{ Router::staticFilePath("js/users/overview.js") }}";
        UserOverview.init();
    </script>
@endcomponent
