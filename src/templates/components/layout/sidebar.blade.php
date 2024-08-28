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
    @auth(2)
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
    @endauth
</ul>
