<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));

$get = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "group" => CommonValidators::group(false, [], t("The group that should be edited does not exist."))
    ])
    ->validate($_GET, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("groups-overview"));
    });

$group = $get["group"];

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router->generate("dashboard"),
        "iconComponent" => "icons.dashboard"
    ],
    [
        "name" => t("Groups"),
        "link" => Router->generate("groups-overview")
    ],
    [
        "name" => isset($group) ? t("Edit group \$\$name\$\$", ["name" => $group->getName()]) : t("Create group"),
        "link" => Router->generate(isset($group) ? "groups-edit" : "groups-create", isset($group) ? ["group" => $group->getId()] : [])
    ]
];

echo Blade->run("pages.groups.edit", [
    "breadcrumbs" => $breadcrumbs,
    "group" => $group ?? null
]);
