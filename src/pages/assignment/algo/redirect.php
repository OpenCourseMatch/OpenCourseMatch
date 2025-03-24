<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

$algorithmRunning = SystemStatus::dao()->get("algorithmRunning") === "true";
$coursesAssigned = SystemStatus::dao()->get("coursesAssigned") === "true";

if($algorithmRunning) {
    new InfoMessage(t("The course assignment algorithm is currently running. Please wait until it has finished."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("index"));
}

if($coursesAssigned) {
    Comm::redirect(Router::generate("course-assignment-edit"));
} else {
    new InfoMessage(t("An error has occurred whilst attempting to assign the courses to the participants. Please try again later."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("index"));
}
