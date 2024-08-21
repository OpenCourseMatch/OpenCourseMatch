<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

if(isset($_GET["userId"]) && is_numeric($_GET["userId"])) {
    $userId = intval($_GET["userId"]);
    $account = User::dao()->getObject(["id" => $userId, "permissionLevel" => PermissionLevel::FACILITATOR->value]);
    if(!$account instanceof User) {
        new InfoMessage(t("The facilitator that should be edited does not exist."), InfoMessageType::ERROR);
        Comm::redirect(Router::generate("facilitators-overview"));
    }
}

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router::generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ],
    [
        "name" => t("Facilitators"),
        "link" => Router::generate("facilitators-overview")
    ],
    [
        "name" => t(isset($account) ? "Edit facilitator" : "Create facilitator"),
        "link" => Router::generate(isset($account) ? "facilitators-edit" : "facilitators-create", isset($account) ? ["userId" => $account->getId()] : [])
    ]
];

echo Blade->run("facilitators.edit", [
    "breadcrumbs" => $breadcrumbs,
    "user" => $account ?? null
]);
