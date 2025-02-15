<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

$userActionsAllowed = SystemStatus::dao()->get("userActionsAllowed") === "true";
$newUserActionsAllowed = $userActionsAllowed ? "false" : "true";
SystemStatus::dao()->set("userActionsAllowed", $newUserActionsAllowed);

if($newUserActionsAllowed === "true") {
    $message = new InfoMessage(t("The course selection has been enabled."), InfoMessageType::SUCCESS);
} else {
    $message = new InfoMessage(t("The course selection has been disabled."), InfoMessageType::SUCCESS);
}
Comm::redirect(Router::generate("index"));
