<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router::generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ],
    [
        "name" => t("Groups"),
        "link" => Router::generate("groups-overview")
    ]
];

echo Blade->run("groups.overview", [
    "breadcrumbs" => $breadcrumbs
]);
