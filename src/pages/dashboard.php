<?php

$user = Auth::enforceLogin(PermissionLevel::USER->value, Router::generate("index"));

if($user->getPermissionLevel() === PermissionLevel::ADMIN->value) {
    if(SystemStatus::dao()->get("algorithmRunning") === "true") {
        new InfoMessage(t("The course assignment algorithm is currently running. Meanwhile, some actions from your dashboard might be unavailable."), InfoMessageType::WARNING);
    }
}

$variables = [];
if($user->getPermissionLevel() === PermissionLevel::ADMIN->value) {
    $users = count(User::dao()->getObjects([
        "permissionLevel" => PermissionLevel::USER->value
    ]));
    $variables["numberOfParticipantsAndTutors"] = $users;

    $courses = count(Course::dao()->getObjects());
    $variables["numberOfCourses"] = $courses;
}

echo Blade->run("dashboard", $variables);
