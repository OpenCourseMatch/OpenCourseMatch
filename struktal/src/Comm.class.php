<?php

use JetBrains\PhpStorm\NoReturn;

class Comm {
    /**
     * Redirects the user to the given path
     * @param string $redirectPath
     * @return void
     */
    #[NoReturn]
    public static function redirect(string $redirectPath): void {
        header("Location: " . $redirectPath);
        exit;
    }

    /**
     * Sends a JSON response and exits
     * @param array $data
     * @return void
     */
    #[NoReturn]
    public static function sendJson(array $data): void {
        header("Content-Type: application/json");
        echo json_encode($data);
        exit;
    }

    /**
     * Sends a JSON response together with a response code and message and exits
     * This method also sets the HTTP response code with the value of $response["code"]
     * @param array $response
     * @param array $data
     * @return void
     */
    #[NoReturn]
    public static function apiSendJson(array $response, array $data): void {
        header("Content-Type: application/json");
        http_response_code($response["code"]);
        echo json_encode([
            "code" => $response["code"],
            "message" => $response["message"],
            "data" => $data
        ]);
        exit;
    }

    private function __construct() {}
}
