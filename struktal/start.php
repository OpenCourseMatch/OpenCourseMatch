<?php

// Autoload Composer libraries
require_once(__APP_DIR__ . "/vendor/autoload.php");

// ClassLoader
require_once(__APP_DIR__ . "/struktal/src/ClassLoader.class.php");
$classLoader = ClassLoader::getInstance();

// Setup utility Composer libraries
use \struktal\Config\StruktalConfig;
StruktalConfig::setConfigFilePath(__APP_DIR__ . "/config/config.json");
const Config = new StruktalConfig();

use \struktal\Logger\Logger;
use \struktal\Logger\LogLevel;
Logger::setLogDirectory(__APP_DIR__ . "/logs/");
Logger::setMinLogLevel(LogLevel::tryFrom(Config->getLogLevel()) ?? LogLevel::TRACE);
const Logger = new Logger("App");

// Load project files
$classLoader->loadDirectory(__APP_DIR__ . "/src/lib/");

unset($classLoader);

// Setup Composer libraries
use \struktal\Router\Router;
Router::setPagesDirectory(__APP_DIR__ . "/src/pages/");
Router::setAppUrl(Config->getAppUrl());
Router::setAppBaseUri(Config->getBaseUri());
Router::setStaticDirectoryUri("static/");
const Router = new Router();

use \struktal\ORM\Database\Database;
if(Config->databaseEnabled()) {
    Database::connect(
        Config->getDatabaseHost(),
        Config->getDatabaseName(),
        Config->getDatabaseUsername(),
        Config->getDatabasePassword()
    );
}

use \struktal\Auth\Auth;
Auth::setUserObjectName(\app\users\User::class);
const Auth = new Auth();

use \struktal\validation\ValidationBuilder;
const Validation = new ValidationBuilder();

use \struktal\Translator\Translator;
use \struktal\Translator\LanguageUtil;
Translator::setTranslationsDirectory(__APP_DIR__ . "/src/translations/");
Translator::setDomain("messages");
Translator::setLocale(LanguageUtil::getPreferredLocale());
const Translator = new Translator();

use eftec\bladeone\BladeOne;
const Blade = new BladeOne(__APP_DIR__ . "/src/templates", __APP_DIR__ . "/template-cache", BladeOne::MODE_DEBUG);

use \struktal\MailWrapper\MailWrapper;
MailWrapper::setSetupFunction(function(MailWrapper $mailWrapper) {
    $mailWrapper->isSMTP();
    $mailWrapper->Host = Config->getSmtpHost();
    $mailWrapper->Port = Config->getSmtpPort();
    $mailWrapper->SMTPAuth = Config->getSmtpAuth();
    $mailWrapper->Username = Config->getSmtpUsername();
    $mailWrapper->Password = Config->getSmtpPassword();
    $mailWrapper->SMTPSecure = Config->getSmtpSecure();
    $mailWrapper->CharSet = "UTF-8";
});
MailWrapper::setRedirectAllMails(
    Config->redirectAllMails(),
    Config->getRedirectMailAddress()
);

use \struktal\InfoMessage\InfoMessageHandler;
const InfoMessage = new InfoMessageHandler();

use \struktal\ComposerReader\ComposerReader;
ComposerReader::setProjectDirectory(__APP_DIR__);
const ComposerReader = new ComposerReader();

// Override BladeOne's include directive to use components with isolated variables
Blade->directive("include", function($expression) {
    $code = Blade->phpTag . " Blade->startComponent($expression); ?>";
    $code .= Blade->phpTag . ' echo Blade->renderComponent(); ?>';
    return $code;
});

// Setup logger
$sendEmailHandler = function(string $formattedMessage, string $serializedMessage, mixed $originalMessage) {
    if(empty(Config->getLogRecipients())) {
        return;
    }

    $mail = new struktal\MailWrapper\MailWrapper();
    $mail->Subject = "[" . Config->getAppName() . "] Error report";
    $mail->Body = $formattedMessage;
    foreach(Config->getLogRecipients() as $recipient) {
        $mail->addAddress($recipient);
    }
    $mail->send();
};
Logger::addCustomLogHandler(LogLevel::ERROR, $sendEmailHandler);
Logger::addCustomLogHandler(LogLevel::FATAL, $sendEmailHandler);
unset($sendEmailHandler);

// Initialize routes
require_once(__APP_DIR__ . "/src/config/app-routes.php");

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    $message = "Error " . $errno . ": ";
    $message .= "\"" . $errstr . "\"";
    $message .= " in " . $errfile . " on line " . $errline;
    try {
        if($errno === E_USER_NOTICE) {
            Logger->tag("PHP")->info($message);
            return;
        } else if($errno === E_USER_WARNING) {
            Logger->tag("PHP")->warn($message);
            return;
        } else if($errno === E_USER_DEPRECATED) {
            Logger->tag("PHP")->warn($message);
            return;
        }

        Logger->tag("PHP")->error($message);
    } catch(Error|Exception $e) {
        // If the logger fails, log to the default PHP error log
        error_log($message);
    }

    if(Config->isProduction()) {
        // Redirect to error page in production
        Router->redirect(Router->generate("500"));
    } else {
        // Show stack trace screen in development
        echo Blade->run("shells.deverror", [
            "exceptionName" => "Error " . $errno,
            "exceptionMessage" => $errstr,
            "trace" => [
                [
                    "file" => $errfile,
                    "line" => $errline
                ]
            ]
        ]);
    }
});

set_exception_handler(function($exception) {
    $message = "Uncaught " . get_class($exception) . ": ";
    $message .= "\"" . $exception->getMessage() . "\"";
    $message .= " in " . $exception->getFile() . " on line " . $exception->getLine();
    $message .= PHP_EOL . $exception->getTraceAsString();

    try {
        Logger->tag("PHP")->fatal($message);
    } catch(Error|Exception $e) {
        error_log($message);
    }

    if(Config->isProduction()) {
        // Redirect to error page in production
        Router->redirect(Router->generate("500"));
    } else {
        // Show stack trace screen in development
        $trace = $exception->getTrace();
        echo Blade->run("shells.deverror", [
            "exceptionName" => get_class($exception),
            "exceptionMessage" => $exception->getMessage(),
            "trace" => [
                [
                    "file" => $exception->getFile(),
                    "line" => $exception->getLine()
                ],
                ...$trace
            ]
        ]);
    }
});

// Setup timezone
date_default_timezone_set("UTC");
