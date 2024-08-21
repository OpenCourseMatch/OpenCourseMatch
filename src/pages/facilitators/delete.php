<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

if(isset($_GET["userId"]) && is_numeric($_GET["userId"])) {
    $userId = intval($_GET["userId"]);
    $account = User::dao()->getObject(["id" => $userId, "permissionLevel" => PermissionLevel::FACILITATOR->value]);
    if(!$account instanceof User) {
        new InfoMessage(t("The facilitator that should be deleted does not exist."), InfoMessageType::ERROR);
        Comm::redirect(Router::generate("facilitators-overview"));
    }

    User::dao()->delete($account);
    new InfoMessage(t("The facilitator has been deleted."), InfoMessageType::SUCCESS);
    Comm::redirect(Router::generate("facilitators-overview"));
} else {
    new InfoMessage(t("The facilitator that should be deleted does not exist."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("facilitators-overview"));
}
