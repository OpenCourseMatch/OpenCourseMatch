<?php

// Router settings
Config::$ROUTER_SETTINGS["ROUTER_BASE_URI"] = Config::configSecret()["ROUTER_SETTINGS"]["ROUTER_BASE_URI"] ?? "/";

// Project settings
Config::$PROJECT_SETTINGS["PROJECT_NAME"] = Config::configSecret()["PROJECT_SETTINGS"]["PROJECT_NAME"] ?? "Project";
Config::$PROJECT_SETTINGS["WEBSITE_TITLE"] = Config::configSecret()["PROJECT_SETTINGS"]["WEBSITE_TITLE"] ?? "Project";
Config::$PROJECT_SETTINGS["PROJECT_URL"] = Config::configSecret()["PROJECT_SETTINGS"]["PROJECT_URL"] ?? "http://localhost:3000";
Config::$PROJECT_SETTINGS["PROJECT_FAVICON"] = Config::configSecret()["PROJECT_SETTINGS"]["PROJECT_FAVICON"] ?? Router::staticFilePath("img/favicon.png");
Config::$PROJECT_SETTINGS["PROJECT_AUTHOR"] = Config::configSecret()["PROJECT_SETTINGS"]["PROJECT_AUTHOR"] ?? "Author";
Config::$PROJECT_SETTINGS["PROJECT_VERSION"] = Config::configSecret()["PROJECT_SETTINGS"]["PROJECT_VERSION"] ?? "1.0.0";
Config::$PROJECT_SETTINGS["TIMEZONE"] = Config::configSecret()["PROJECT_SETTINGS"]["TIMEZONE"] ?? "Europe/Berlin";

// Menu settings
Config::$MENU_SETTINGS["MENU_SIDEBAR"] = [
    "Home" => [
        "route" => Router::generate("index")
    ]
];

// Time format settings
Config::$DATETIME_SETTINGS["DATE_TECHNICAL"] = "Y-m-d";
Config::$DATETIME_SETTINGS["TIME_TECHNICAL"] = "H:i:s";
Config::$DATETIME_SETTINGS["DATETIME_TECHNICAL"] = "Y-m-d H:i:s";
Config::$DATETIME_SETTINGS["DATE_VISUAL"] = "d.m.Y";
Config::$DATETIME_SETTINGS["TIME_VISUAL"] = "H:i";
Config::$DATETIME_SETTINGS["DATETIME_VISUAL"] = "d.m.Y H:i";

// Log settings
Config::$LOG_SETTINGS["LOG_DIRECTORY"] = Config::configSecret()["LOG_SETTINGS"]["LOG_DIRECTORY"] ?? __APP_DIR__ . "/logs/";
Config::$LOG_SETTINGS["LOG_FILENAME"] = Config::configSecret()["LOG_SETTINGS"]["LOG_FILENAME"] ?? "log-%date%.log";
Config::$LOG_SETTINGS["LOG_LEVEL"] = Config::configSecret()["LOG_SETTINGS"]["LOG_TRACE"] ?? Logger::$LOG_TRACE;
Config::$LOG_SETTINGS["LOG_ERROR_REPORT"] = Config::configSecret()["LOG_SETTINGS"]["LOG_ERROR_REPORT"] ?? [];

// Database settings
Config::$DB_SETTINGS["DB_HOST"] = Config::configSecret()["DATABASE_SETTINGS"]["DB_HOST"] ?? "localhost";
Config::$DB_SETTINGS["DB_USER"] = Config::configSecret()["DATABASE_SETTINGS"]["DB_USER"] ?? "username";
Config::$DB_SETTINGS["DB_PASS"] = Config::configSecret()["DATABASE_SETTINGS"]["DB_PASS"] ?? "password";
Config::$DB_SETTINGS["DB_NAME"] = Config::configSecret()["DATABASE_SETTINGS"]["DB_NAME"] ?? "database";
Config::$DB_SETTINGS["DB_USE"] = Config::configSecret()["DATABASE_SETTINGS"]["DB_USE"] ?? true;

// Mail settings
Config::$MAIL_SETTINGS["MAIL_SMTP_HOST"] = Config::configSecret()["MAIL_SETTINGS"]["MAIL_SMTP_HOST"] ?? "smtp.localhost";
Config::$MAIL_SETTINGS["MAIL_SMTP_PORT"] = Config::configSecret()["MAIL_SETTINGS"]["MAIL_SMTP_PORT"] ?? 25;
Config::$MAIL_SETTINGS["MAIL_SMTP_USER"] = Config::configSecret()["MAIL_SETTINGS"]["MAIL_SMTP_USER"] ?? "username";
Config::$MAIL_SETTINGS["MAIL_SMTP_PASS"] = Config::configSecret()["MAIL_SETTINGS"]["MAIL_SMTP_PASS"] ?? "password";
Config::$MAIL_SETTINGS["MAIL_SMTP_SECURE"] = Config::configSecret()["MAIL_SETTINGS"]["MAIL_SMTP_SECURE"] ?? "tls";
Config::$MAIL_SETTINGS["MAIL_SMTP_AUTH"] = Config::configSecret()["MAIL_SETTINGS"]["MAIL_SMTP_AUTH"] ?? true;
Config::$MAIL_SETTINGS["MAIL_DEFAULT_SENDER_EMAIL"] = Config::configSecret()["MAIL_SETTINGS"]["MAIL_DEFAULT_SENDER_EMAIL"] ?? "mail@framework";
Config::$MAIL_SETTINGS["MAIL_DEFAULT_SENDER_NAME"] = Config::configSecret()["MAIL_SETTINGS"]["MAIL_DEFAULT_SENDER_NAME"] ?? "Framework";
Config::$MAIL_SETTINGS["MAIL_DEFAULT_REPLY_TO"] = Config::configSecret()["MAIL_SETTINGS"]["MAIL_DEFAULT_REPLY_TO"] ?? "mail@framework";
Config::$MAIL_SETTINGS["MAIL_DEFAULT_SUBJECT"] = Config::configSecret()["MAIL_SETTINGS"]["MAIL_DEFAULT_SUBJECT"] ?? "Framework";
Config::$MAIL_SETTINGS["MAIL_REDIRECT_ALL_MAILS"] = Config::configSecret()["MAIL_SETTINGS"]["MAIL_REDIRECT_ALL_MAILS"] ?? false;
Config::$MAIL_SETTINGS["MAIL_REDIRECT_ALL_MAILS_TO"] = Config::configSecret()["MAIL_SETTINGS"]["MAIL_REDIRECT_ALL_MAILS_TO"] ?? "mail@framework";

// ClassLoader settings
Config::$CLASS_LOADER_SETTINGS["CLASS_LOADER_IGNORE_FILES"][] = "Config.class.php";
Config::$CLASS_LOADER_SETTINGS["CLASS_LOADER_IGNORE_FILES"][] = "Logger.class.php";
Config::$CLASS_LOADER_SETTINGS["CLASS_LOADER_IGNORE_FILES"][] = "ClassLoader.class.php";
Config::$CLASS_LOADER_SETTINGS["CLASS_LOADER_IMPORT_PATHS"][] = __APP_DIR__ . "/project/src/lib/";
Config::$CLASS_LOADER_SETTINGS["CLASS_LOADER_IMPORT_PATHS"][] = __APP_DIR__ . "/project/src/object/";
Config::$CLASS_LOADER_SETTINGS["CLASS_LOADER_IMPORT_PATHS"][] = __APP_DIR__ . "/project/src/dao/";

// SEO settings
Config::$SEO_SETTINGS["SEO_DEFAULT_DESCRIPTION"] = Config::configSecret()["SEO_SETTINGS"]["SEO_DEFAULT_DESCRIPTION"] ?? "Description";
Config::$SEO_SETTINGS["SEO_KEYWORDS"] = Config::configSecret()["SEO_SETTINGS"]["SEO_KEYWORDS"] ?? [];
Config::$SEO_SETTINGS["SEO_IMAGE_PREVIEW"] = Config::configSecret()["SEO_SETTINGS"]["SEO_IMAGE_PREVIEW"] ?? Router::staticFilePath("img/seo/preview.png");
Config::$SEO_SETTINGS["SEO_OPENGRAPH"] = Config::configSecret()["SEO_SETTINGS"]["SEO_OPENGRAPH"] ?? ["OPENGRAPH_SITE_NAME" => null];
Config::$SEO_SETTINGS["SEO_TWITTER"] = Config::configSecret()["SEO_SETTINGS"]["SEO_TWITTER"] ?? ["TWITTER_SITE" => null, "TWITTER_CREATOR" => null];
Config::$SEO_SETTINGS["SEO_ROBOTS"] = Config::configSecret()["SEO_SETTINGS"]["SEO_ROBOTS"] ?? ["index", "follow"];
Config::$SEO_SETTINGS["SEO_REVISIT"] = Config::configSecret()["SEO_SETTINGS"]["SEO_REVISIT"] ?? "1 days";
