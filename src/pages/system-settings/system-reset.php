<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router->generate("dashboard"),
        "iconComponent" => "icons.dashboard"
    ],
    [
        "name" => t("Reset system data"),
        "link" => Router->generate("system-reset")
    ]
];

echo Blade->run("pages.systemsettings.systemreset", [
    "breadcrumbs" => $breadcrumbs
]);
