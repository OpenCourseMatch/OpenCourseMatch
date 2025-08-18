<ul class="flex flex-col gap-2 p-4">
    @auth
        @component("components.layout.sidebars.sidebaritem", [
            "href" => Router->generate("dashboard"),
            "icon" => "components.icons.dashboard",
            "active" => in_array(Router->getCalledRouteName(), [ "dashboard" ])
        ])
            {{ t("Dashboard") }}
        @endcomponent
        @component("components.layout.sidebars.sidebaritem", [
            "href" => Router->generate("account-settings"),
            "icon" => "components.icons.accountsettings",
            "active" => in_array(Router->getCalledRouteName(), [ "account-settings", "account-settings-change-password" ])
        ])
            {{ t("Account settings") }}
        @endcomponent
    @endauth

    @auth(PermissionLevel::USER->value)
        @if(SystemStatus::dao()->get("userActionsAllowed") === "true")
            <span class="text-lg font-bold mt-4">
                {{ t("Courses") }}
            </span>
            @component("components.layout.sidebars.sidebaritem", [
                "href" => Router->generate("choice-edit"),
                "icon" => "components.icons.course",
                "active" => in_array(Router->getCalledRouteName(), [ "choice-edit" ])
            ])
                {{ t("Choose courses") }}
            @endcomponent
        @endif
    @endauth

    @auth(PermissionLevel::FACILITATOR->value)
        <span class="text-lg font-bold mt-4">
            {{ t("Manage accounts") }}
        </span>
        @component("components.layout.sidebars.sidebaritem", [
            "href" => Router->generate("users-overview"),
            "icon" => "components.icons.user",
            "active" => in_array(Router->getCalledRouteName(), [ "users-overview", "users-create", "users-edit", "users-import", "group-actions" ])
        ])
            {{ t("Participants and tutors") }}
        @endcomponent

        <span class="text-lg font-bold mt-4">
            {{ t("Manage courses") }}
        </span>
        @component("components.layout.sidebars.sidebaritem", [
            "href" => Router->generate("courses-overview"),
            "icon" => "components.icons.course",
            "active" => in_array(Router->getCalledRouteName(), [ "courses-overview", "courses-create", "courses-edit" ])
        ])
            {{ t("Courses") }}
        @endcomponent
    @endauth

    @auth(PermissionLevel::ADMIN->value)
        <span class="text-lg font-bold mt-4">
            {{ t("Manage accounts") }}
        </span>
        @component("components.layout.sidebars.sidebaritem", [
            "href" => Router->generate("groups-overview"),
            "icon" => "components.icons.group",
            "active" => in_array(Router->getCalledRouteName(), [ "groups-overview", "groups-create", "groups-edit" ])
        ])
            {{ t("Groups") }}
        @endcomponent
        @component("components.layout.sidebars.sidebaritem", [
            "href" => Router->generate("users-overview"),
            "icon" => "components.icons.user",
            "active" => in_array(Router->getCalledRouteName(), [ "users-overview", "users-create", "users-edit", "users-import", "group-actions", "choice-edit-others" ])
        ])
            {{ t("Participants and tutors") }}
        @endcomponent
        @component("components.layout.sidebars.sidebaritem", [
            "href" => Router->generate("facilitators-overview"),
            "icon" => "components.icons.user",
            "active" => in_array(Router->getCalledRouteName(), [ "facilitators-overview", "facilitators-create", "facilitators-edit" ])
        ])
            {{ t("Facilitators") }}
        @endcomponent
        @component("components.layout.sidebars.sidebaritem", [
            "href" => Router->generate("administrators-overview"),
            "icon" => "components.icons.user",
            "active" => in_array(Router->getCalledRouteName(), [ "administrators-overview", "administrators-create", "administrators-edit" ])
        ])
            {{ t("Administrators") }}
        @endcomponent

        <span class="text-lg font-bold mt-4">
            {{ t("Manage courses") }}
        </span>
        @component("components.layout.sidebars.sidebaritem", [
            "href" => Router->generate("courses-overview"),
            "icon" => "components.icons.course",
            "active" => in_array(Router->getCalledRouteName(), [ "courses-overview", "courses-create", "courses-edit" ])
        ])
            {{ t("Courses") }}
        @endcomponent

        @if(SystemStatus::dao()->get("coursesAssigned") !== "true" && SystemStatus::dao()->get("algorithmRunning") !== "true")
            <span class="text-lg font-bold mt-4">
                {{ t("Course assignment") }}
            </span>
            @component("components.layout.sidebars.sidebaritem", [
                "href" => Router->generate("course-assignment-run"),
                "icon" => "components.icons.algorithm",
                "active" => in_array(Router->getCalledRouteName(), [ "course-assignment-run" ])
            ])
                {{ t("Run course assignment") }}
            @endcomponent
        @elseif(SystemStatus::dao()->get("coursesAssigned") === "true")
            <span class="text-lg font-bold mt-4">
                {{ t("Course assignment") }}
            </span>
            @component("components.layout.sidebars.sidebaritem", [
                "href" => Router->generate("course-assignment-edit"),
                "icon" => "components.icons.assignment",
                "active" => in_array(Router->getCalledRouteName(), [ "course-assignment-edit" ])
            ])
                {{ t("Edit course assignment") }}
            @endcomponent
            @component("components.layout.sidebars.sidebaritem", [
                "href" => Router->generate("course-assignment-export"),
                "icon" => "components.icons.export",
                "active" => in_array(Router->getCalledRouteName(), [ "course-assignment-export" ])
            ])
                {{ t("Export course assignment") }}
            @endcomponent
            {{-- TODO: Reset --}}
        @endif

        <span class="text-xl font-bold mt-4">
            {{ t("Statistics") }}
        </span>
        @component("components.layout.sidebars.sidebaritem", [
            "href" => Router->generate("statistics-overview"),
            "icon" => "components.icons.statistics",
            "active" => in_array(Router->getCalledRouteName(), [ "statistics-overview" ])
        ])
            {{ t("Statistics") }}
        @endcomponent

        <span class="text-xl font-bold mt-4">
            {{ t("Settings") }}
        </span>
        @component("components.layout.sidebars.sidebaritem", [
            "href" => Router->generate("system-settings"),
            "icon" => "components.icons.gear",
            "active" => in_array(Router->getCalledRouteName(), [ "system-settings" ])
        ])
            {{ t("System settings") }}
        @endcomponent
        @component("components.layout.sidebars.sidebaritem", [
            "href" => Router->generate("system-reset"),
            "icon" => "components.icons.reset",
            "active" => in_array(Router->getCalledRouteName(), [ "system-reset" ])
        ])
            {{ t("Reset system data") }}
        @endcomponent
    @endauth
</ul>
