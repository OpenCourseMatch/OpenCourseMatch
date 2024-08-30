<?php

$user = Auth::enforceLogin(PermissionLevel::FACILITATOR->value, Router::generate("index"));

if(isset($_GET["courseId"]) && is_numeric($_GET["courseId"])) {
    $courseId = intval($_GET["courseId"]);
    $course = Course::dao()->getObject(["id" => $courseId]);
    if(!$course instanceof Course) {
        new InfoMessage(t("The course that should be deleted does not exist."), InfoMessageType::ERROR);
        Comm::redirect(Router::generate("courses-overview"));
    }

    Course::dao()->delete($course);
    new InfoMessage(t("The course has been deleted."), InfoMessageType::SUCCESS);
    Comm::redirect(Router::generate("courses-overview"));
} else {
    new InfoMessage(t("The course that should be deleted does not exist."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("courses-overview"));
}
