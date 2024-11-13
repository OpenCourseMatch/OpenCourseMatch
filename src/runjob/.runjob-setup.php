<?php

// Only allow this script to be run from the command line
if(php_sapi_name() !== "cli") {
    echo "This script can only be run from the command line.";
    exit(1);
}

// Application directory: The directory where the application is located in the filesystem
chdir("../../");
const __APP_DIR__ = __DIR__ . "/../..";

require_once(__APP_DIR__ . "/framework/framework.php");
