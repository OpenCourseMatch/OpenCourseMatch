<?php

require __DIR__ . "/.runjob-setup.php";

Logger::getLogger("AllocationAlgorithm")->info("Starting allocation algorithm");

$algorithm = new AllocationAlgorithm();
$algorithm->run();

Logger::getLogger("AllocationAlgorithm")->info("Allocation algorithm finished");
