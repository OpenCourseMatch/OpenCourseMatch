<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

if(isset($_GET["userId"]) && is_numeric($_GET["userId"])) {
    $userId = intval($_GET["userId"]);
    $account = User::dao()->getObject(["id" => $userId, "permissionLevel" => PermissionLevel::ADMIN->value]);
    if(!$account instanceof User) {
        new InfoMessage(t("The administrator that should be deleted does not exist."), InfoMessageType::ERROR);
        Comm::redirect(Router::generate("administrators-overview"));
    }

    User::dao()->delete($account);
    new InfoMessage(t("The administrator has been deleted."), InfoMessageType::SUCCESS);
    Comm::redirect(Router::generate("administrators-overview"));
} else {
    new InfoMessage(t("The administrator that should be deleted does not exist."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("administrators-overview"));
}
