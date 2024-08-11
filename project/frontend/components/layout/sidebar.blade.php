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
    @endauth
</ul>
