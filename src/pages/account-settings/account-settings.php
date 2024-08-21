<?php

$user = Auth::enforceLogin(PermissionLevel::USER->value, Router::generate("index"));

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router::generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ],
    [
        "name" => t("Account settings"),
        "link" => Router::generate("account-settings")
    ]
];

echo Blade->run("accountsettings.accountsettings", [
    "breadcrumbs" => $breadcrumbs,
]);
