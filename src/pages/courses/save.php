<?php

$user = Auth::enforceLogin(PermissionLevel::FACILITATOR->value, Router::generate("index"));

if(isset($_POST["courseId"]) && is_numeric($_POST["courseId"])) {
    $courseId = intval($_POST["courseId"]);
    $course = Course::dao()->getObject(["id" => $courseId]);
    if(!$course instanceof Course) {
        new InfoMessage(t("The course that should be edited does not exist."), InfoMessageType::ERROR);
        Comm::redirect(Router::generate("courses-overview"));
    }
} else {
    $course = new Course();
}

if(empty($_POST["title"]) || empty($_POST["minClearance"]) || !is_numeric($_POST["minClearance"]) || empty($_POST["maxParticipants"]) || !is_numeric($_POST["maxParticipants"]) || intval($_POST["maxParticipants"]) < 0) {
    new InfoMessage(t("Please fill out all the required fields."), InfoMessageType::ERROR);
    if(isset($courseId)) {
        Comm::redirect(Router::generate("courses-edit", ["courseId" => $courseId]));
    } else {
        Comm::redirect(Router::generate("courses-create"));
    }
}

$minClearance = intval($_POST["minClearance"]);
$maxClearance = !empty($_POST["maxClearance"]) && is_numeric($_POST["maxClearance"]) ? intval($_POST["maxClearance"]) : null;

if($minClearance > $maxClearance) {
    new InfoMessage(t("The minimum clearance level must be lower than the maximum clearance level."), InfoMessageType::ERROR);
    if(isset($courseId)) {
        Comm::redirect(Router::generate("courses-edit", ["courseId" => $courseId]));
    } else {
        Comm::redirect(Router::generate("courses-create"));
    }
}

$course->setTitle($_POST["title"]);
$course->setOrganizer(!empty($_POST["organizer"]) ? $_POST["organizer"] : null);
$course->setMinClearance(intval($_POST["minClearance"]));
$course->setMaxClearance(!empty($_POST["maxClearance"]) && is_numeric($_POST["maxClearance"]) ? intval($_POST["maxClearance"]) : null);
$course->setMaxParticipants(intval($_POST["maxParticipants"]));
Course::dao()->save($course);

new InfoMessage(t("The course has been saved."), InfoMessageType::SUCCESS);
Comm::redirect(Router::generate("courses-overview"));
