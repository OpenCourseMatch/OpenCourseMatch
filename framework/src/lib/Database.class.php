<?php

class Database {
    private static ?Database $instance = null;
    private ?PDO $connection = null;

    private function __construct() {
        if(Config::$DB_SETTINGS["DB_USE"]) {
            $this->connection = new PDO("mysql:host=" . Config::$DB_SETTINGS["DB_HOST"] . ";dbname=" . Config::$DB_SETTINGS["DB_NAME"], Config::$DB_SETTINGS["DB_USER"], Config::$DB_SETTINGS["DB_PASS"], [PDO::MYSQL_ATTR_FOUND_ROWS => true]);
        }
    }

    /**
     * Returns the database instance
     * @return Database
     */
    public static function getInstance(): Database {
        if(self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    /**
     * Returns the PDO database connection
     * @return PDO|null
     */
    public static function getConnection(): ?PDO {
        return self::getInstance()->connection;
    }
}
