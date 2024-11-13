<?php

require __DIR__ . "/.runjob-setup.php";

$algorithm = new AllocationAlgorithm();
$algorithm->run();
