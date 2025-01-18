<?php

class Config {
    private static ?array $secretConfig = null;

    // Router settings
    public static array $ROUTER_SETTINGS;

    // Application settings
    public static array $APP_SETTINGS = [];

    // Log settings
    public static array $LOG_SETTINGS;

    // Database settings
    public static array $DB_SETTINGS;

    // Mail settings
    public static array $MAIL_SETTINGS;

    // ClassLoader settings
    public static array $CLASS_LOADER_SETTINGS;

    // SEO settings
    public static array $SEO_SETTINGS;

    /**
     * Stores some placeholder config values
     * They are overridden by src/config/app-config.php
     */
    public static function init(): void {
        self::$ROUTER_SETTINGS = [
            "ROUTER_BASE_URI" => "/"
        ];

        self::$APP_SETTINGS = [
            "APP_NAME" => "Application",
            "WEBSITE_TITLE" => "Application",
            "APP_URL" => "http://localhost:3000",
            "APP_FAVICON" => Router::staticFilePath("img/favicon.png"),
            "APP_AUTHOR" => "Author",
            "APP_VERSION" => "1.0.0"
        ];

        self::$LOG_SETTINGS = [
            "LOG_DIRECTORY" => __APP_DIR__ . "/logs/",
            "LOG_FILENAME" => "log-%date%.log",
            "LOG_LEVEL" => Logger::$LOG_DEBUG
        ];

        self::$DB_SETTINGS = [
            "DB_HOST" => "localhost",
            "DB_USER" => "username",
            "DB_PASS" => "password",
            "DB_NAME" => "database",
            "DB_USE" => true
        ];

        self::$MAIL_SETTINGS = [
            "MAIL_DEFAULT_SENDER_EMAIL" => "mail@framework",
            "MAIL_DEFAULT_SENDER_NAME" => "Framework",
            "MAIL_DEFAULT_REPLY_TO" => "reply@framework",
            "MAIL_DEFAULT_SUBJECT" => "Framework Mail",
            "MAIL_REDIRECT_ALL_MAILS" => false,
            "MAIL_REDIRECT_ALL_MAILS_TO" => "redirect@framework"
        ];

        self::$CLASS_LOADER_SETTINGS = [
            "CLASS_LOADER_IGNORE_FILES" => [],
            "CLASS_LOADER_IMPORT_PATHS" => []
        ];

        self::$SEO_SETTINGS = [
            "SEO_DEFAULT_DESCRIPTION" => "Description",
            "SEO_KEYWORDS" => [],
            "SEO_IMAGE_PREVIEW" => Router::staticFilePath("img/seo/preview.png"),
            "SEO_OPENGRAPH" => [
                "OPENGRAPH_SITE_NAME" => null,
            ],
            "SEO_TWITTER" => [
                "TWITTER_SITE" => null,
                "TWITTER_CREATOR" => null
            ],
            "SEO_ROBOTS" => [
                "index", "follow"
            ],
            "SEO_REVISIT" => "1 days"
        ];
    }

    /**
     * Loads the secret config
     * @return array
     */
    public static function configSecret(): array {
        if(self::$secretConfig !== null) {
            return self::$secretConfig;
        }

        if(file_exists(__APP_DIR__ . "/config/config.json")) {
            self::$secretConfig = json_decode(file_get_contents(__APP_DIR__ . "/config/config.json"), true) ?? [];
        } else {
            self::$secretConfig = [];
        }

        return self::$secretConfig;
    }

    private function __construct() {}
}
