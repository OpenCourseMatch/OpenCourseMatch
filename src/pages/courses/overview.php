<?php

$user = Auth::enforceLogin(PermissionLevel::FACILITATOR->value, Router::generate("index"));

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router::generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ],
    [
        "name" => t("Courses"),
        "link" => Router::generate("courses-overview")
    ]
];

echo Blade->run("courses.overview", [
    "breadcrumbs" => $breadcrumbs
]);
