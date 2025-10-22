<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::FACILITATOR, Router->generate("index"));

$groups = \app\groups\Group::dao()->getObjects();

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
        "name" => t("Group actions"),
        "link" => Router->generate("group-actions")
    ]
];

echo Blade->run("pages.users.group", [
    "breadcrumbs" => $breadcrumbs,
    "groups" => $groups
]);
