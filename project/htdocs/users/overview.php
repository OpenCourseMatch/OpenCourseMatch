<?php

$user = Auth::enforceLogin(PermissionLevel::FACILITATOR->value, Router::generate("index"));

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router::generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ],
    [
        "name" => t("Participants and tutors"),
        "link" => Router::generate("users-overview")
    ]
];

echo Blade->run("users.overview", [
    "breadcrumbs" => $breadcrumbs
]);
