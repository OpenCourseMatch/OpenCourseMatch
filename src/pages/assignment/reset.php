<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));
$coursesAssigned = SystemStatus::dao()->get("coursesAssigned") === "true";

if(!$coursesAssigned) {
    new InfoMessage(t("An error has occurred whilst attempting to reset the course assignment. Please try again later."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("index"));
}

$assignments = Assignment::dao()->getObjects();
foreach($assignments as $assignment) {
    Assignment::dao()->delete($assignment);
}

SystemStatus::dao()->set("coursesAssigned", "false");

new InfoMessage(t("The course assignment has been reset."), InfoMessageType::SUCCESS);
Comm::redirect(Router::generate("index"));
