<?php

$user = Auth->enforceLogin(PermissionLevel::ADMIN->value, Router->generate("index"));

$algorithmRunning = SystemStatus::dao()->get("algorithmRunning") === "true";
$coursesAssigned = SystemStatus::dao()->get("coursesAssigned") === "true";

if($algorithmRunning) {
    InfoMessage->error(t("The course assignment algorithm is currently running. Please wait until it has finished."));
    Router->redirect(Router->generate("index"));
}

if($coursesAssigned) {
    InfoMessage->error(t("The courses have already been assigned. Please reset the course assignment before running the algorithm again."));
    Router->redirect(Router->generate("index"));
}

exec("php " . __APP_DIR__ . "/src/runjob/assignment-algorithm.php > /dev/null 2>&1 &");

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router->generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ]
];

echo Blade->run("assignment.algo", [
    "breadcrumbs" => $breadcrumbs
]);
