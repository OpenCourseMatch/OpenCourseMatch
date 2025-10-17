<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));

$userActionsAllowed = SystemStatus::dao()->get("userActionsAllowed") === "true";

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router->generate("dashboard"),
        "iconComponent" => "icons.dashboard"
    ],
    [
        "name" => $userActionsAllowed ? t("Disable course selection") : t("Enable course selection"),
        "link" => Router->generate("choice-state-toggle")
    ]
];

echo Blade->run("pages.choice-state.toggle", [
    "userActionsAllowed" => $userActionsAllowed,
    "breadcrumbs" => $breadcrumbs
]);
