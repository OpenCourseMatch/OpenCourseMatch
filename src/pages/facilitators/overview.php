<?php

$user = Auth->enforceLogin(PermissionLevel::ADMIN->value, Router->generate("index"));

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router->generate("dashboard"),
        "iconComponent" => "icons.dashboard"
    ],
    [
        "name" => t("Facilitators"),
        "link" => Router->generate("facilitators-overview")
    ]
];

echo Blade->run("pages.facilitators.overview", [
    "breadcrumbs" => $breadcrumbs
]);
