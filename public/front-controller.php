<?php

// Application directory: The directory where the application is located in the filesystem
chdir("../");
const __APP_DIR__ = __DIR__ . "/..";

require_once(__APP_DIR__ . "/struktal/start.php");

session_start();

$loggedInUser = Auth->getLoggedInUser();
if($loggedInUser instanceof \app\users\User) {
    Blade->setAuth($loggedInUser->getUsername(), $loggedInUser->getPermissionLevel()->value);
}
unset($loggedInUser);

// Global translation function
function t(string $message, array $variables = []): string {
    return Translator->translate($message, $variables);
}

// Start the router
Router->startRouter();
