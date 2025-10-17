<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));

$algorithmRunning = SystemStatus::dao()->get("algorithmRunning") === "true";
$coursesAssigned = SystemStatus::dao()->get("coursesAssigned") === "true";

if($algorithmRunning) {
    InfoMessage->error(t("The course assignment algorithm is currently running. Please wait until it has finished."));
    Router->redirect(Router->generate("index"));
}

if($coursesAssigned) {
    Router->redirect(Router->generate("course-assignment-edit"));
} else {
    InfoMessage->error(t("An error has occurred whilst attempting to assign the courses to the participants. Please try again later."));
    Router->redirect(Router->generate("index"));
}
