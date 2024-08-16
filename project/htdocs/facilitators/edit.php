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

echo Blade->run("facilitators.edit", [
    "user" => $account ?? null
]);
