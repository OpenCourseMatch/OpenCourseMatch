<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

$settings = SystemSetting::dao()->getObjects();
$defaultValues = SystemSetting::dao()->defaultValues();

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router::generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ],
    [
        "name" => t("System settings"),
        "link" => Router::generate("system-settings")
    ]
];

echo Blade->run("systemsettings.systemsettings", [
    "breadcrumbs" => $breadcrumbs,
    "settings" => $settings,
    "defaultValues" => $defaultValues
]);
