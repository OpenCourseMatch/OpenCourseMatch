<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::USER, Router->generate("index"));

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router->generate("dashboard"),
        "iconComponent" => "icons.dashboard"
    ],
    [
        "name" => t("Account settings"),
        "link" => Router->generate("account-settings")
    ]
];

echo Blade->run("pages.accountsettings.accountsettings", [
    "breadcrumbs" => $breadcrumbs,
    "user" => $user
]);
