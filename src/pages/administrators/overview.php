<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router::generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ],
    [
        "name" => t("Administrators"),
        "link" => Router::generate("administrators-overview")
    ]
];

echo Blade->run("administrators.overview", [
    "breadcrumbs" => $breadcrumbs
]);
