<?php

const __APP_DIR__ = __DIR__ . "/..";

// Autoload Composer libraries
require_once(__APP_DIR__ . "/vendor/autoload.php");

\struktal\core\StruktalCore::start(__APP_DIR__, \app\users\User::class);
