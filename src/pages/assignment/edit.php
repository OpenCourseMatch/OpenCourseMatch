<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));
$coursesAssigned = SystemStatus::dao()->get("coursesAssigned") === "true";

if(!$coursesAssigned) {
    new InfoMessage(t("An error has occurred whilst attempting to edit the course assignment. Please try again later."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("index"));
}

$courses = Course::dao()->getObjects([], "minClearance");
$courseIds = [null];
foreach($courses as $course) {
    $courseIds[] = $course->getId();
}

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router::generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ],
    [
        "name" => t("Edit course assignment"),
        "link" => Router::generate("course-assignment-edit")
    ]
];

echo Blade->run("assignment.edit", [
    "breadcrumbs" => $breadcrumbs,
    "courseIds" => $courseIds
]);
