<?php

$user = Auth::enforceLogin(PermissionLevel::HELPER->value, Router::generate("index"));

if(isset($_GET["userId"]) && is_numeric($_GET["userId"])) {
    $userId = intval($_GET["userId"]);
    $account = User::dao()->getObject(["id" => $userId, "permissionLevel" => PermissionLevel::USER->value]);
    if(!$account instanceof User) {
        new InfoMessage(t("The user that should be deleted does not exist."), InfoMessageType::ERROR);
        Comm::redirect(Router::generate("users-overview"));
    }

    User::dao()->delete($account);
    new InfoMessage(t("The user has been deleted."), InfoMessageType::SUCCESS);
    Comm::redirect(Router::generate("users-overview"));
} else {
    new InfoMessage(t("The user that should be deleted does not exist."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("users-overview"));
}
