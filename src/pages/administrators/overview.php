<?php

$user = Auth->enforceLogin(PermissionLevel::ADMIN->value, Router->generate("index"));

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router->generate("dashboard"),
        "iconComponent" => "icons.dashboard"
    ],
    [
        "name" => t("Administrators"),
        "link" => Router->generate("administrators-overview")
    ]
];

echo Blade->run("pages.administrators.overview", [
    "breadcrumbs" => $breadcrumbs
]);
