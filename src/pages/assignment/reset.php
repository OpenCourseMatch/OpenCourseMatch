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
        "link" => Router->generate("course-assignment-reset")
    ]
];

echo Blade->run("pages.assignment.reset", [
    "breadcrumbs" => $breadcrumbs
]);
