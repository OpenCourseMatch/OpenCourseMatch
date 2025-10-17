<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));

$settings = SystemSetting::dao()->getObjects();
$defaultValues = SystemSetting::dao()->defaultValues();

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router->generate("dashboard"),
        "iconComponent" => "icons.dashboard"
    ],
    [
        "name" => t("System settings"),
        "link" => Router->generate("system-settings")
    ]
];

echo Blade->run("pages.systemsettings.systemsettings", [
    "breadcrumbs" => $breadcrumbs,
    "settings" => $settings,
    "defaultValues" => $defaultValues
]);
