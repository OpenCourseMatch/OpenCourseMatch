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
        "name" => t("Group actions"),
        "link" => Router::generate("group-actions")
    ]
];

echo Blade->run("users.group", [
    "breadcrumbs" => $breadcrumbs,
    "groups" => $groups
]);
