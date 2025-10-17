<?php

use \app\assignments\Assignment;
use \app\settings\SystemStatus;

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));
$coursesAssigned = \app\settings\SystemStatus::dao()->get("coursesAssigned") === "true";

if(!$coursesAssigned) {
    InfoMessage->error(t("An error has occurred whilst attempting to reset the course assignment. Please try again later."));
    Router->redirect(Router->generate("index"));
}

$assignments = Assignment::dao()->getObjects();
foreach($assignments as $assignment) {
    Assignment::dao()->delete($assignment);
}

SystemStatus::dao()->set("coursesAssigned", "false");

InfoMessage->success(t("The course assignment has been reset."));
Router->redirect(Router->generate("index"));
