<ul>
    @component("components.layout.sidebarlistitem", [
        "href" => Router::generate("index"),
    ])
        {{ t("Home") }}
    @endcomponent
</ul>
