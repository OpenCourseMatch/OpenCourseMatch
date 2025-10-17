<?php

use \app\courses\Course;

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));
$coursesAssigned = \app\settings\SystemStatus::dao()->get("coursesAssigned") === "true";

if(!$coursesAssigned) {
    InfoMessage->error(t("An error has occurred whilst attempting to edit the course assignment. Please try again later."));
    Router->redirect(Router->generate("index"));
}

$courses = Course::dao()->getObjects([], "minClearance");
$courseIds = [null];
foreach($courses as $course) {
    $courseIds[] = $course->getId();
}

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router->generate("dashboard"),
        "iconComponent" => "icons.dashboard"
    ],
    [
        "name" => t("Edit course assignment"),
        "link" => Router->generate("course-assignment-edit")
    ]
];

echo Blade->run("pages.assignment.edit", [
    "breadcrumbs" => $breadcrumbs,
    "courseIds" => $courseIds
]);
