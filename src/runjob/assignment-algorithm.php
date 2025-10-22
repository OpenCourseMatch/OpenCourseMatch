<?php

require __DIR__ . "/.runjob-setup.php";

if(\app\settings\SystemStatus::dao()->get("algorithmRunning") === "true") {
    Logger->tag("AssignmentAlgorithm")->info("Aborting assignment algorithm because it is already running");
    exit;
}

try {
    $algorithm = new AssignmentAlgorithm();
    $algorithm->run();
} catch(Exception $e) {
    \app\settings\SystemStatus::dao()->set("algorithmRunning", "false");
    \app\settings\SystemStatus::dao()->set("coursesAssigned", "false");
    throw $e;
}
