<?php

$user = Auth->enforceLogin(PermissionLevel::ADMIN->value, Router->generate("index"));

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router->generate("dashboard"),
        "iconComponent" => "icons.dashboard"
    ],
    [
        "name" => t("Reset system data"),
        "link" => Router->generate("course-assignment-reset-confirm")
    ]
];

echo Blade->run("pages.assignment.reset-confirm", [
    "breadcrumbs" => $breadcrumbs
]);
