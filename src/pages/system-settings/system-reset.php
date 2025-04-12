<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router::generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ],
    [
        "name" => t("Reset system data"),
        "link" => Router::generate("system-reset")
    ]
];

echo Blade->run("systemsettings.systemreset", [
    "breadcrumbs" => $breadcrumbs
]);
