<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router->generate("dashboard"),
        "iconComponent" => "icons.dashboard"
    ],
    [
        "name" => t("Groups"),
        "link" => Router->generate("groups-overview")
    ]
];

echo Blade->run("pages.groups.overview", [
    "breadcrumbs" => $breadcrumbs
]);
