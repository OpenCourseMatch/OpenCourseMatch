<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::FACILITATOR, Router->generate("index"));

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router->generate("dashboard"),
        "iconComponent" => "icons.dashboard"
    ],
    [
        "name" => t("Courses"),
        "link" => Router->generate("courses-overview")
    ]
];

echo Blade->run("pages.courses.overview", [
    "breadcrumbs" => $breadcrumbs
]);
