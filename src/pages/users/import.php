<?php

$user = Auth::enforceLogin(PermissionLevel::FACILITATOR->value, Router::generate("index"));

$groups = Group::dao()->getObjects();

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router::generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ],
    [
        "name" => t("Participants and tutors"),
        "link" => Router::generate("users-overview")
    ],
    [
        "name" => t("Import users"),
        "link" => Router::generate("users-import")
    ]
];

echo Blade->run("users.import", [
    "breadcrumbs" => $breadcrumbs,
    "groups" => $groups
]);
