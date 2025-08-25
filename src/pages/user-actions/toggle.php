<?php

$user = Auth->enforceLogin(PermissionLevel::ADMIN->value, Router->generate("index"));

$userActionsAllowed = SystemStatus::dao()->get("userActionsAllowed") === "true";
$newUserActionsAllowed = $userActionsAllowed ? "false" : "true";
SystemStatus::dao()->set("userActionsAllowed", $newUserActionsAllowed);

if($newUserActionsAllowed === "true") {
    $message = InfoMessage->success(t("The course selection has been enabled."));
} else {
    $message = InfoMessage->success(t("The course selection has been disabled."));
}
Router->redirect(Router->generate("index"));
