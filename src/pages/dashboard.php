<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::USER, Router->generate("index"));

if($user->getPermissionLevel() === \app\users\PermissionLevel::ADMIN) {
    if(\app\settings\SystemStatus::dao()->get("algorithmRunning") === "true") {
        InfoMessage->warning(t("The course assignment algorithm is currently running. Meanwhile, some actions from your dashboard might be unavailable."));
    }
}

$variables = [];
if($user->getPermissionLevel() === \app\users\PermissionLevel::ADMIN) {
    $users = count(\app\users\User::dao()->getObjects([
        "permissionLevel" => \app\users\PermissionLevel::USER
    ]));
    $variables["numberOfParticipantsAndTutors"] = $users;

    $courses = count(\app\courses\Course::dao()->getObjects());
    $variables["numberOfCourses"] = $courses;
}
if($user->getPermissionLevel() === \app\users\PermissionLevel::USER) {
    if(\app\settings\SystemStatus::dao()->get("courseAssignmentPublic") === "true") {
        $assignedCourse = \app\assignments\AssignmentService::getAssignedCourseForUser($user);
        $variables["assignedCourse"] = $assignedCourse;

        $leadingCourse = $user->getLeadingCourse();
        $leadingCourseWasCancelled = $leadingCourse instanceof \app\courses\Course ? \app\courses\CourseService::isCourseCancelled($leadingCourse) : false;
        $variables["leadingCourseWasCancelled"] = $leadingCourseWasCancelled;
    }
}

echo Blade->run("pages.dashboard", $variables);
