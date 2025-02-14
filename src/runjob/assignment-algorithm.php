<?php

require __DIR__ . "/.runjob-setup.php";

if(SystemStatus::dao()->get("algorithmRunning") === "true") {
    Logger::getLogger("AssignmentAlgorithm")->info("Aborting assignment algorithm because it is already running");
    exit;
}

try {
    $algorithm = new AssignmentAlgorithm();
    $algorithm->run();
} catch(Exception $e) {
    SystemStatus::dao()->set("algorithmRunning", "false");
    SystemStatus::dao()->set("coursesAssigned", "false");
    throw $e;
}
