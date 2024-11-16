<?php

// Autoload Composer libraries
require_once(__APP_DIR__ . "/vendor/autoload.php");

// ClassLoader
require_once(__APP_DIR__ . "/framework/src/ClassLoader.class.php");
$classLoader = ClassLoader::getInstance();

// Load Logger
$classLoader->loadClass(__APP_DIR__ . "/framework/src/Logger.class.php");

// Load Comm
$classLoader->loadClass(__APP_DIR__ . "/framework/src/Comm.class.php");

// Load Router
$classLoader->loadClass(__APP_DIR__ . "/framework/src/Router.class.php");

// Configuration files
require_once(__APP_DIR__ . "/framework/config/Config.class.php");
Config::init();
require_once(__APP_DIR__ . "/src/config/app-config.php");

// Initialize routes
require_once(__APP_DIR__ . "/src/config/app-routes.php");

// Load enums
$classLoader->loadEnums(__APP_DIR__ . "/framework/src/enum/");

// Load libraries
$classLoader->loadClasses(__APP_DIR__ . "/framework/src/lib/");

// Load objects
$classLoader->loadClasses(__APP_DIR__ . "/framework/src/object/");

// Load DAOs
$classLoader->loadClasses(__APP_DIR__ . "/framework/src/dao/");

// Load extra enums and classes
foreach(Config::$CLASS_LOADER_SETTINGS["CLASS_LOADER_IMPORT_PATHS"] as $path) {
    $classLoader->loadEnums($path);
    $classLoader->loadClasses($path);
}

unset($classLoader);

// Setup Composer libraries
use eftec\bladeone\BladeOne;
const Blade = new BladeOne(__APP_DIR__ . "/src/templates", __APP_DIR__ . "/template-cache", BladeOne::MODE_DEBUG);

// Setup logger
$sendEmailHandler = function(string $message) {
    if(empty(Config::$LOG_SETTINGS["LOG_ERROR_REPORT"])) {
        return;
    }

    $mail = new Mail();
    $mail->setSubject("[" . Config::$APP_SETTINGS["APP_NAME"] . "] Error report")
        ->setTextBody($message);
    foreach(Config::$LOG_SETTINGS["LOG_ERROR_REPORT"] as $recipient) {
        $mail->addRecipient($recipient);
    }
    $mail->send();
};
Logger::addCustomLogHandler(Logger::$LOG_ERROR, $sendEmailHandler);
Logger::addCustomLogHandler(Logger::$LOG_FATAL, $sendEmailHandler);
unset($sendEmailHandler);

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    $message = "Error " . $errno . ": ";
    $message .= "\"" . $errstr . "\"";
    $message .= " in " . $errfile . " on line " . $errline;
    Logger::getLogger("PHP")->error($message);
});

set_exception_handler(function($exception) {
    $message = "Uncaught " . get_class($exception) . ": ";
    $message .= "\"" . $exception->getMessage() . "\"";
    $message .= " in " . $exception->getFile() . " on line " . $exception->getLine();
    $message .= PHP_EOL . $exception->getTraceAsString();
    Logger::getLogger("PHP")->fatal($message);
});

// Setup timezone
date_default_timezone_set("UTC");
