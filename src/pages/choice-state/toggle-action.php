<?php

use \app\settings\SystemStatus;

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));

$userActionsAllowed = isset($_POST["userActionsAllowed"]) && $_POST["userActionsAllowed"] === "1";

SystemStatus::dao()->set("userActionsAllowed", $userActionsAllowed ? "true" : "false");
if($userActionsAllowed) {
    InfoMessage->success(t("The course selection has been enabled."));
} else {
    InfoMessage->success(t("The course selection has been disabled."));
}

Router->redirect(Router->generate("index"));
