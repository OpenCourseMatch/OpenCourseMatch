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

        <div class="flex flex-col w-full justify-center">
            <canvas id="statistics-groups"></canvas>
        </div>

        <div class="flex flex-col w-full justify-center">
            <canvas id="statistics-choices"></canvas>
        </div>

        <div class="flex flex-col w-full justify-center">
            <canvas id="statistics-choices-by-group"></canvas>
        </div>
    </div>

    <h2 class="mt-4 mb-2">
        {{ t("Courses") }}
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        <div class="flex flex-col w-full justify-center">
            <canvas id="statistics-courses"></canvas>
        </div>

        <div class="flex flex-col w-full justify-center">
            <canvas id="statistics-courses-by-group"></canvas>
        </div>

        <div class="flex flex-col w-full justify-center">
            <canvas id="statistics-places"></canvas>
        </div>

        <div class="flex flex-col w-full justify-center">
            <canvas id="statistics-places-by-group"></canvas>
        </div>
    </div>

    <h2 class="mt-4 mb-2">
        {{ t("Assignments") }}
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        <div class="flex flex-col w-full justify-center">
            <canvas id="statistics-assignments"></canvas>
        </div>

        <div class="flex flex-col w-full justify-center">
            <canvas id="statistics-assignments-by-group"></canvas>
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
            title: "{{ t("User types") }}",
            dataLabel: "{{ t("Accounts") }}",
            participant: "{{ t("Participant") }}",
            tutor: "{{ t("Tutor") }}"
        }, [
            {{ $statistics["userTypes"]["participant"] }},
            {{ $statistics["userTypes"]["tutor"] }}
        ]);

        StatisticsOverview.initGroupsChart({
            title: "{{ t("Groups") }}",
            dataLabel: "{{ t("Accounts") }}",
            defaultGroup: "{{ t("Default group") }}"
        }, @json($statistics["groups"]), @json($customGroups));

        StatisticsOverview.initChoicesChart({
            title: "{{ t("Choices") }}",
            dataLabel: "{{ t("Accounts") }}",
            complete: "{{ t("Complete") }}",
            incomplete: "{{ t("Incomplete") }}",
            missing: "{{ t("Missing") }}"
        }, [
            {{ $statistics["choices"]["complete"] }},
            {{ $statistics["choices"]["incomplete"] }},
            {{ $statistics["choices"]["missing"] }}
        ]);

        StatisticsOverview.initChoicesByGroupChart({
            title: "{{ t("Choices (by group)") }}",
            dataLabel: "{{ t("Accounts") }}",
            defaultGroup: "{{ t("Default group") }}",
            complete: "{{ t("Complete") }}",
            incomplete: "{{ t("Incomplete") }}",
            missing: "{{ t("Missing") }}"
        }, @json($statistics["choicesByGroup"]), @json($customGroups));

        StatisticsOverview.initCoursesChart({
            title: "{{ t("Courses") }}",
            dataLabel: "{{ t("Courses") }}",
            user: "{{ t("Led by users") }}",
            facilitator: "{{ t("Led by facilitators") }}",
            cancelled: "{{ t("Cancelled") }}"
        }, [
            {{ $statistics["courseLeaderships"]["user"] }},
            {{ $statistics["courseLeaderships"]["facilitator"] }},
            {{ $statistics["courseLeaderships"]["cancelled"] }}
        ]);

        StatisticsOverview.initCoursesByGroupChart({
            title: "{{ t("Courses (by group)") }}",
            dataLabel: "{{ t("Courses") }}",
            defaultGroup: "{{ t("Default group") }}"
        }, @json($statistics["coursesByGroup"]), @json($customGroups));

        StatisticsOverview.initPlacesChart({
            title: "{{ t("Places") }}",
            dataLabel: "{{ t("Places") }}",
            available: "{{ t("Available") }}",
            occupied: "{{ t("Occupied") }}",
            cancelled: "{{ t("Cancelled") }}"
        }, [
            {{ $statistics["places"]["available"] }},
            {{ $statistics["places"]["occupied"] }},
            {{ $statistics["places"]["cancelled"] }}
        ]);

        StatisticsOverview.initPlacesByGroupChart({
            title: "{{ t("Places (by group)") }}",
            dataLabel: "{{ t("Places") }}",
            defaultGroup: "{{ t("Default group") }}",
            available: "{{ t("Available") }}",
            occupied: "{{ t("Occupied") }}",
            cancelled: "{{ t("Cancelled") }}"
        }, @json($statistics["placesByGroup"]), @json($customGroups));

        StatisticsOverview.initAssignmentsChart({
            title: "{{ t("Assignments") }}",
            dataLabel: "{{ t("Users") }}",
            assigned: "{{ t("Assigned") }}",
            notAssigned: "{{ t("Not assigned") }}",
            noChoice: "{{ t("No courses chosen") }}"
        }, [
            {{ $statistics["assignments"]["assigned"] }},
            {{ $statistics["assignments"]["notAssigned"] }},
            {{ $statistics["assignments"]["noChoice"] }}
        ]);

        StatisticsOverview.initAssignmentsByGroupChart({
            title: "{{ t("Assignments (by group)") }}",
            dataLabel: "{{ t("Users") }}",
            defaultGroup: "{{ t("Default group") }}",
            assigned: "{{ t("Assigned") }}",
            notAssigned: "{{ t("Not assigned") }}",
            noChoice: "{{ t("No courses chosen") }}"
        }, @json($statistics["assignmentsByGroup"]), @json($customGroups));
    </script>
@endcomponent
