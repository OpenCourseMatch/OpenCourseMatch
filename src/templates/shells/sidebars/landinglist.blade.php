<ul class="flex flex-col gap-2 p-6">
    @component("shells.sidebars.sidebaritem", [
        "href" => Router->generate("index"),
        "icon" => "icons.home",
        "active" => in_array(Router->getCalledRouteName(), [ "index" ])
    ])
        {{ t("Home") }}
    @endcomponent
</ul>
