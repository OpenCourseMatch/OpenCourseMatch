<?php

// Only allow this script to be run from the command line
if(php_sapi_name() !== "cli") {
    echo "This script can only be run from the command line.";
    exit(1);
}

require_once("../../struktal/start.php");
