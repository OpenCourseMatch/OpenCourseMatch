<ul class="flex flex-col gap-2 p-4">
    @component("components.layout.sidebars.sidebaritem", [
        "href" => Router->generate("index"),
        "icon" => "components.icons.home",
        "active" => in_array(Router->getCalledRouteName(), [ "console" ])
    ])
        {{ t("Home") }}
    @endcomponent
</ul>
