<?php

require __DIR__ . "/.runjob-setup.php";

if(SystemStatus::dao()->get("algorithmRunning") === "true") {
    Logger::getLogger("AllocationAlgorithm")->info("Aborting allocation algorithm because it is already running");
    exit;
}

try {
    $algorithm = new AllocationAlgorithm();
    $algorithm->run();
} catch(Exception $e) {
    SystemStatus::dao()->set("algorithmRunning", "false");
    SystemStatus::dao()->set("coursesAssigned", "false");
    throw $e;
}
