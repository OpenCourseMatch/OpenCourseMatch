<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));

$courseAssignmentPublic = isset($_POST["courseAssignmentPublic"]) && $_POST["courseAssignmentPublic"] === "1";

\app\settings\SystemStatus::dao()->set("courseAssignmentPublic", $courseAssignmentPublic ? "true" : "false");
if($courseAssignmentPublic) {
    InfoMessage->success(t("The course assignment has been published."));
} else {
    InfoMessage->success(t("The course assignment has been hidden."));
}

Router->redirect(Router->generate("index"));
