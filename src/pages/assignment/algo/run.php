<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

$algorithmRunning = SystemStatus::dao()->get("algorithmRunning") === "true";
$coursesAssigned = SystemStatus::dao()->get("coursesAssigned") === "true";

if($algorithmRunning) {
    new InfoMessage(t("The course assignment algorithm is currently running. Please wait until it has finished."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("index"));
}

if($coursesAssigned) {
    new InfoMessage(t("The courses have already been assigned. Please reset the course assignment before running the algorithm again."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("index"));
}

exec("php " . __APP_DIR__ . "/src/runjob/assignment-algorithm.php > /dev/null 2>&1 &");

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router::generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ]
];

echo Blade->run("assignment.algo", [
    "breadcrumbs" => $breadcrumbs
]);
