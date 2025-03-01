@component("components.layout.appshell", [
    "title" => t("Statistics"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Statistics") }}
    </h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        <div class="flex flex-col w-full justify-center">
            <canvas id="statistics-account-types"></canvas>
        </div>

        <div class="flex flex-col w-full justify-center">
            <canvas id="statistics-user-types"></canvas>
        </div>
    </div>

    <script type="module">
        import * as StatisticsOverview from "{{ Router::staticFilePath("js/statistics/overview.js") }}";

        StatisticsOverview.initAccountTypesChart({
            title: "{{ t("Account types") }}",
            dataLabel: "{{ t("Accounts") }}",
            user: "{{ t("User") }}",
            facilitator: "{{ t("Facilitator") }}",
            admin: "{{ t("Administrator") }}"
        }, [
            {{ $statistics["accountTypes"]["user"] }},
            {{ $statistics["accountTypes"]["facilitator"] }},
            {{ $statistics["accountTypes"]["administrator"] }}
        ]);

        StatisticsOverview.initUserTypesChart({
            title: "{{ t("Account types") }}",
            dataLabel: "{{ t("Accounts") }}",
            participant: "{{ t("Participant") }}",
            tutor: "{{ t("Tutor") }}"
        }, [
            {{ $statistics["userTypes"]["participant"] }},
            {{ $statistics["userTypes"]["tutor"] }}
        ]);
    </script>
@endcomponent
