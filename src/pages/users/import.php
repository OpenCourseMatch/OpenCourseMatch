<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::FACILITATOR, Router->generate("index"));

$groups = Group::dao()->getObjects();

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router->generate("dashboard"),
        "iconComponent" => "icons.dashboard"
    ],
    [
        "name" => t("Participants and tutors"),
        "link" => Router->generate("users-overview")
    ],
    [
        "name" => t("Import users"),
        "link" => Router->generate("users-import")
    ]
];

echo Blade->run("pages.users.import", [
    "breadcrumbs" => $breadcrumbs,
    "groups" => $groups
]);
