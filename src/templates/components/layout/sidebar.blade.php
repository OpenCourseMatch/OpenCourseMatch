<ul>
    @guest
        @component("components.layout.sidebarlistitem", [
            "href" => Router::generate("index"),
        ])
            {{ t("Home") }}
        @endcomponent
    @endguest
    @auth
        @component("components.layout.sidebarlistitem", [
            "href" => Router::generate("dashboard"),
        ])
            {{ t("Dashboard") }}
        @endcomponent
        @component("components.layout.sidebarlistitem", [
            "href" => Router::generate("account-settings"),
        ])
            {{ t("Account settings") }}
        @endcomponent
    @endauth
    @auth(PermissionLevel::USER->value)
        @component("components.layout.sidebarlistitem", [
            "href" => Router::generate("choice-edit"),
        ])
            {{ t("Choose courses") }}
        @endcomponent
    @endauth
    @auth(PermissionLevel::FACILITATOR->value)
        @component("components.layout.sidebarlistitem", [
            "href" => Router::generate("users-overview"),
        ])
            {{ t("Participants and tutors") }}
        @endcomponent
        @component("components.layout.sidebarlistitem", [
            "href" => Router::generate("courses-overview"),
        ])
            {{ t("Courses") }}
        @endcomponent
    @endauth
    @auth(PermissionLevel::ADMIN->value)
        @component("components.layout.sidebarlistitem", [
            "href" => Router::generate("groups-overview"),
        ])
            {{ t("Groups") }}
        @endcomponent
        @component("components.layout.sidebarlistitem", [
            "href" => Router::generate("users-overview"),
        ])
            {{ t("Participants and tutors") }}
        @endcomponent
        @component("components.layout.sidebarlistitem", [
            "href" => Router::generate("facilitators-overview"),
        ])
            {{ t("Facilitators") }}
        @endcomponent
        @component("components.layout.sidebarlistitem", [
            "href" => Router::generate("administrators-overview"),
        ])
            {{ t("Administrators") }}
        @endcomponent
        @component("components.layout.sidebarlistitem", [
            "href" => Router::generate("courses-overview"),
        ])
            {{ t("Courses") }}
        @endcomponent
        @if(SystemStatus::dao()->get("coursesAssigned") === "true")
            @component("components.layout.sidebarlistitem", [
                "href" => Router::generate("course-assignment-edit"),
            ])
                {{ t("Edit course assignment") }}
            @endcomponent
        @endif
        @component("components.layout.sidebarlistitem", [
            "href" => Router::generate("statistics-overview"),
        ])
            {{ t("Statistics") }}
        @endcomponent
        @component("components.layout.sidebarlistitem", [
            "href" => Router::generate("system-settings"),
        ])
            {{ t("System settings") }}
        @endcomponent
    @endauth
    {{-- TODO: Changelog --}}
</ul>
