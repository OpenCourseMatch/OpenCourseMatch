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
        @component("components.layout.sidebars.sidebaritem", [
            "href" => Router->generate("choice-edit"),
            "icon" => "components.icons.course",
            "active" => in_array(Router->getCalledRouteName(), [ "choice-edit" ])
        ])
            {{ t("Choose courses") }}
        @endcomponent
    @endauth
    @auth(PermissionLevel::FACILITATOR->value)
        @component("components.layout.sidebars.sidebaritem", [
            "href" => Router->generate("users-overview"),
            "icon" => "components.icons.user",
            "active" => in_array(Router->getCalledRouteName(), [ "users-overview", "users-create", "users-edit", "users-import", "group-actions" ])
        ])
            {{ t("Participants and tutors") }}
        @endcomponent
        @component("components.layout.sidebars.sidebaritem", [
            "href" => Router->generate("courses-overview"),
            "icon" => "components.icons.course",
            "active" => in_array(Router->getCalledRouteName(), [ "courses-overview", "courses-create", "courses-edit" ])
        ])
            {{ t("Courses") }}
        @endcomponent
    @endauth
    @auth(PermissionLevel::ADMIN->value)
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
        @component("components.layout.sidebars.sidebaritem", [
            "href" => Router->generate("courses-overview"),
            "icon" => "components.icons.course",
            "active" => in_array(Router->getCalledRouteName(), [ "courses-overview", "courses-create", "courses-edit" ])
        ])
            {{ t("Courses") }}
        @endcomponent
        @if(SystemStatus::dao()->get("coursesAssigned") === "true")
            @component("components.layout.sidebars.sidebaritem", [
                "href" => Router->generate("course-assignment-edit"),
                "icon" => "components.icons.assignment",
                "active" => in_array(Router->getCalledRouteName(), [ "course-assignment-edit" ])
            ])
                {{ t("Edit course assignment") }}
            @endcomponent
        @endif
        @component("components.layout.sidebars.sidebaritem", [
            "href" => Router->generate("statistics-overview"),
            "icon" => "components.icons.statistics",
            "active" => in_array(Router->getCalledRouteName(), [ "statistics-overview" ])
        ])
            {{ t("Statistics") }}
        @endcomponent
        @component("components.layout.sidebars.sidebaritem", [
            "href" => Router->generate("system-settings"),
            "icon" => "components.icons.gear",
            "active" => in_array(Router->getCalledRouteName(), [ "system-settings", "system-reset" ])
        ])
            {{ t("System settings") }}
        @endcomponent
    @endauth
</ul>
