<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));

$algorithmRunning = \app\settings\SystemStatus::dao()->get("algorithmRunning") === "true";
$coursesAssigned = \app\settings\SystemStatus::dao()->get("coursesAssigned") === "true";

if($algorithmRunning) {
    InfoMessage->error(t("The course assignment algorithm is currently running. Please wait until it has finished."));
    Router->redirect(Router->generate("index"));
}

if($coursesAssigned) {
    InfoMessage->error(t("The courses have already been assigned. Please reset the course assignment before running the algorithm again."));
    Router->redirect(Router->generate("index"));
}

exec("cd " . __APP_DIR__ . "/src/runjobs && php85 assignment-algorithm.php > /dev/null 2>&1 &");

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router->generate("dashboard"),
        "iconComponent" => "icons.dashboard"
    ]
];

echo Blade->run("pages.assignment.algo", [
    "breadcrumbs" => $breadcrumbs
]);
