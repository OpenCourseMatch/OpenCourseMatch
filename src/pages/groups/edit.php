<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

$validation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "group" => \validation\Validator::create([
            \validation\IsInDatabase::create(Group::dao())->setErrorMessage(t("The group that should be edited does not exist."))
        ])
    ])
]);
try {
    $get = $validation->getValidatedValue($_GET);
} catch(\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("groups-overview"));
}

$group = $get["group"];

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router::generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ],
    [
        "name" => t("Groups"),
        "link" => Router::generate("groups-overview")
    ],
    [
        "name" => isset($group) ? t("Edit group \$\$name\$\$", ["name" => $group->getName()]) : t("Create group"),
        "link" => Router::generate(isset($group) ? "groups-edit" : "groups-create", isset($group) ? ["group" => $group->getId()] : [])
    ]
];

echo Blade->run("groups.edit", [
    "breadcrumbs" => $breadcrumbs,
    "group" => $group ?? null
]);
