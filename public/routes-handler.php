<?php

// Application directory: The directory where the application is located in the filesystem
chdir("../");
const __APP_DIR__ = __DIR__ . "/..";

require_once(__APP_DIR__ . "/framework/framework.php");

session_start();

$loggedInUser = Auth::getLoggedInUser();
if($loggedInUser instanceof User) {
    Blade->setAuth($loggedInUser->getUsername(), $loggedInUser->getPermissionLevel());
}
unset($loggedInUser);

// Set UI language
Translator::setDomain("messages");
Translator::setLocale(TranslationUtil::getPreferredLocale());

function t(string $message, array $variables = []): string {
    return Translator::translate($message, $variables);
}

// Start the router
$router = new Router();
$router->startRouter();
