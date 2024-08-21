<?php

$user = Auth::enforceLogin(PermissionLevel::FACILITATOR->value, Router::generate("index"));

if(isset($_GET["userId"]) && is_numeric($_GET["userId"])) {
    $userId = intval($_GET["userId"]);
    $account = User::dao()->getObject(["id" => $userId, "permissionLevel" => PermissionLevel::USER->value]);
    if(!$account instanceof User) {
        new InfoMessage(t("The user that should be edited does not exist."), InfoMessageType::ERROR);
        Comm::redirect(Router::generate("users-overview"));
    }
}

$groups = Group::dao()->getObjects();

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router::generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ],
    [
        "name" => t("Participants and tutors"),
        "link" => Router::generate("users-overview")
    ],
    [
        "name" => t(isset($account) ? "Edit user" : "Create user"),
        "link" => Router::generate(isset($account) ? "users-edit" : "users-create", isset($account) ? ["userId" => $account->getId()] : [])
    ]
];

echo Blade->run("users.edit", [
    "breadcrumbs" => $breadcrumbs,
    "user" => $account ?? null,
    "groups" => $groups
]);
