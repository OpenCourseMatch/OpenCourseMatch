<ul class="flex flex-col gap-2 p-4">
    @auth
        @component("shells.sidebars.sidebaritem", [
            "href" => Router->generate("dashboard"),
            "icon" => "icons.dashboard",
            "active" => in_array(Router->getCalledRouteName(), [ "dashboard" ])
        ])
            {{ t("Dashboard") }}
        @endcomponent
        @component("shells.sidebars.sidebaritem", [
            "href" => Router->generate("account-settings"),
            "icon" => "icons.accountsettings",
            "active" => in_array(Router->getCalledRouteName(), [ "account-settings", "account-settings-change-password" ])
        ])
            {{ t("Account settings") }}
        @endcomponent
    @endauth

    @auth(\app\users\PermissionLevel::USER->value)
        @if(\app\settings\SystemStatus::dao()->get("userActionsAllowed") === "true")
            <span class="text-lg font-bold mt-4">
                {{ t("Courses") }}
            </span>
            @component("shells.sidebars.sidebaritem", [
                "href" => Router->generate("choice-edit"),
                "icon" => "icons.course",
                "active" => in_array(Router->getCalledRouteName(), [ "choice-edit" ])
            ])
                {{ t("Choose courses") }}
            @endcomponent
        @endif
    @endauth

    @auth(\app\users\PermissionLevel::FACILITATOR->value)
        <span class="text-lg font-bold mt-4">
            {{ t("Manage accounts") }}
        </span>
        @component("shells.sidebars.sidebaritem", [
            "href" => Router->generate("users-overview"),
            "icon" => "icons.user",
            "active" => in_array(Router->getCalledRouteName(), [ "users-overview", "users-create", "users-edit", "users-import", "group-actions" ])
        ])
            {{ t("Participants and tutors") }}
        @endcomponent

        <span class="text-lg font-bold mt-4">
            {{ t("Manage courses") }}
        </span>
        @component("shells.sidebars.sidebaritem", [
            "href" => Router->generate("courses-overview"),
            "icon" => "icons.course",
            "active" => in_array(Router->getCalledRouteName(), [ "courses-overview", "courses-create", "courses-edit" ])
        ])
            {{ t("Courses") }}
        @endcomponent
    @endauth

    @auth(\app\users\PermissionLevel::ADMIN->value)
        <span class="text-lg font-bold mt-4">
            {{ t("Manage accounts") }}
        </span>
        @component("shells.sidebars.sidebaritem", [
            "href" => Router->generate("groups-overview"),
            "icon" => "icons.group",
            "active" => in_array(Router->getCalledRouteName(), [ "groups-overview", "groups-create", "groups-edit" ])
        ])
            {{ t("Groups") }}
        @endcomponent
        @component("shells.sidebars.sidebaritem", [
            "href" => Router->generate("users-overview"),
            "icon" => "icons.user",
            "active" => in_array(Router->getCalledRouteName(), [ "users-overview", "users-create", "users-edit", "users-import", "group-actions", "choice-edit-others" ])
        ])
            {{ t("Participants and tutors") }}
        @endcomponent
        @component("shells.sidebars.sidebaritem", [
            "href" => Router->generate("facilitators-overview"),
            "icon" => "icons.user",
            "active" => in_array(Router->getCalledRouteName(), [ "facilitators-overview", "facilitators-create", "facilitators-edit" ])
        ])
            {{ t("Facilitators") }}
        @endcomponent
        @component("shells.sidebars.sidebaritem", [
            "href" => Router->generate("administrators-overview"),
            "icon" => "icons.user",
            "active" => in_array(Router->getCalledRouteName(), [ "administrators-overview", "administrators-create", "administrators-edit" ])
        ])
            {{ t("Administrators") }}
        @endcomponent

        <span class="text-lg font-bold mt-4">
            {{ t("Manage courses") }}
        </span>
        @component("shells.sidebars.sidebaritem", [
            "href" => Router->generate("courses-overview"),
            "icon" => "icons.course",
            "active" => in_array(Router->getCalledRouteName(), [ "courses-overview", "courses-create", "courses-edit" ])
        ])
            {{ t("Courses") }}
        @endcomponent

        @if(\app\settings\SystemStatus::dao()->get("coursesAssigned") !== "true" && \app\settings\SystemStatus::dao()->get("algorithmRunning") !== "true")
            <span class="text-lg font-bold mt-4">
                {{ t("Course assignment") }}
            </span>
            @component("shells.sidebars.sidebaritem", [
                "href" => Router->generate("course-assignment-run"),
                "icon" => "icons.algorithm",
                "active" => in_array(Router->getCalledRouteName(), [ "course-assignment-run" ])
            ])
                {{ t("Run course assignment") }}
            @endcomponent
        @elseif(\app\settings\SystemStatus::dao()->get("coursesAssigned") === "true")
            <span class="text-lg font-bold mt-4">
                {{ t("Course assignment") }}
            </span>
            @component("shells.sidebars.sidebaritem", [
                "href" => Router->generate("course-assignment-edit"),
                "icon" => "icons.assignment",
                "active" => in_array(Router->getCalledRouteName(), [ "course-assignment-edit" ])
            ])
                {{ t("Edit course assignment") }}
            @endcomponent
            @component("shells.sidebars.sidebaritem", [
                "href" => Router->generate("course-assignment-export"),
                "icon" => "icons.export",
                "active" => in_array(Router->getCalledRouteName(), [ "course-assignment-export" ])
            ])
                {{ t("Export course assignment") }}
            @endcomponent
            @component("shells.sidebars.sidebaritem", [
                "href" => Router->generate("course-assignment-reset"),
                "icon" => "icons.reset",
                "active" => in_array(Router->getCalledRouteName(), [ "course-assignment-reset" ])
            ])
                {{ t("Reset course assignment") }}
            @endcomponent
        @endif

        <span class="text-xl font-bold mt-4">
            {{ t("Statistics") }}
        </span>
        @component("shells.sidebars.sidebaritem", [
            "href" => Router->generate("statistics-overview"),
            "icon" => "icons.statistics",
            "active" => in_array(Router->getCalledRouteName(), [ "statistics-overview" ])
        ])
            {{ t("Statistics") }}
        @endcomponent

        <span class="text-xl font-bold mt-4">
            {{ t("Settings") }}
        </span>
        @component("shells.sidebars.sidebaritem", [
            "href" => Router->generate("system-settings"),
            "icon" => "icons.gear",
            "active" => in_array(Router->getCalledRouteName(), [ "system-settings" ])
        ])
            {{ t("System settings") }}
        @endcomponent
        @component("shells.sidebars.sidebaritem", [
            "href" => Router->generate("choice-state-toggle"),
            "icon" => "icons.user",
            "active" => in_array(Router->getCalledRouteName(), [ "choice-state-toggle" ])
        ])
            {{ \app\settings\SystemStatus::dao()->get("userActionsAllowed") === "true" ? t("Disable course selection") : t("Enable course selection") }}
        @endcomponent
        @component("shells.sidebars.sidebaritem", [
            "href" => Router->generate("assignment-public-state-toggle"),
            "icon" => "icons.user",
            "active" => in_array(Router->getCalledRouteName(), [ "assignment-public-state-toggle" ])
        ])
            {{ \app\settings\SystemStatus::dao()->get("courseAssignmentPublic") === "true" ? t("Hide course assignment") : t("Publish course assignment") }}
        @endcomponent
        @component("shells.sidebars.sidebaritem", [
            "href" => Router->generate("system-reset"),
            "icon" => "icons.reset",
            "active" => in_array(Router->getCalledRouteName(), [ "system-reset" ])
        ])
            {{ t("Reset system data") }}
        @endcomponent
    @endauth
</ul>
