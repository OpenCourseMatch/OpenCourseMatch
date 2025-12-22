<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));

$courseAssignmentPublic = \app\settings\SystemStatus::dao()->get("courseAssignmentPublic") === "true";

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router->generate("dashboard"),
        "iconComponent" => "icons.dashboard"
    ],
    [
        "name" => $courseAssignmentPublic ? t("Hide course assignment") : t("Publish course assignment"),
        "link" => Router->generate("assignment-public-state-toggle")
    ]
];

echo Blade->run("pages.assignment-public-state.toggle", [
    "courseAssignmentPublic" => $courseAssignmentPublic,
    "breadcrumbs" => $breadcrumbs
]);
