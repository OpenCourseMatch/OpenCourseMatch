<?php

$user = Auth::enforceLogin(PermissionLevel::FACILITATOR->value, Router::generate("index"));

if(isset($_GET["courseId"]) && is_numeric($_GET["courseId"])) {
    $courseId = intval($_GET["courseId"]);
    $course = Course::dao()->getObject(["id" => $courseId]);
    if(!$course instanceof Course) {
        new InfoMessage(t("The course that should be edited does not exist."), InfoMessageType::ERROR);
        Comm::redirect(Router::generate("courses-overview"));
    }
}

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router::generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ],
    [
        "name" => t("Courses"),
        "link" => Router::generate("courses-overview")
    ],
    [
        "name" => t(isset($course) ? "Edit course" : "Create course"),
        "link" => Router::generate(isset($course) ? "courses-edit" : "courses-create", isset($course) ? ["courseId" => $course->getId()] : [])
    ]
];

echo Blade->run("courses.edit", [
    "breadcrumbs" => $breadcrumbs,
    "course" => $course ?? null
]);
