<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

if(isset($_GET["userId"]) && is_numeric($_GET["userId"])) {
    $userId = intval($_GET["userId"]);
    $account = User::dao()->getObject(["id" => $userId, "permissionLevel" => PermissionLevel::ADMIN->value]);
    if(!$account instanceof User) {
        new InfoMessage(t("The administrator that should be edited does not exist."), InfoMessageType::ERROR);
        Comm::redirect(Router::generate("administrators-overview"));
    }
}

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router::generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ],
    [
        "name" => t("Administrators"),
        "link" => Router::generate("administrators-overview")
    ],
    [
        "name" => t(isset($account) ? "Edit administrator" : "Create administrator"),
        "link" => Router::generate(isset($account) ? "administrators-edit" : "administrators-create", isset($account) ? ["userId" => $account->getId()] : [])
    ]
];

echo Blade->run("administrators.edit", [
    "breadcrumbs" => $breadcrumbs,
    "user" => $account ?? null
]);
