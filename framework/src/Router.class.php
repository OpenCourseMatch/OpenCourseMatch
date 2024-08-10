<?php

class Router {
    private static array $routes = [];

    /**
     * Registers a route
     * @param string $method HTTP method
     *                       Multiple methods can be separated with a pipe (|) character, without spaces or other symbols
     * @param string $route Route that should get called
     *                      GET parameters can be added to the route by using the following syntax: {type:name}
     *                      Supported types are b (boolean), d (date (without time)), f (float), i (integer) and s (string)
     *                      Names are used to identify the parameter within the $_GET array
     * @param string $routeTo File that should be executed when the route is called
     * @param string $name Name of the route
     * @return void
     */
    public static function addRoute(string $method, string $route, string $routeTo, string $name): void {
        // Retrieve Parameters from the Route
        $params = [];
        preg_match_all("/\{([bdfis]:[a-zA-Z0-9]+)\}/", $route, $matches);
        foreach($matches[1] as $match) {
            $paramType = explode(":", $match)[0];
            $paramName = str_replace($paramType . ":", "", $match);
            $params[$paramName] = $paramType;
        }

        // Save the Route in the Routes Array
        $methods = explode("|", $method);
        foreach($methods as $method) {
            self::$routes[$method][$route] = [
                "route" => $route,
                "routeTo" => __APP_DIR__ . "/project/htdocs/" . $routeTo,
                "name" => $name,
                "params" => $params
            ];
        }
    }

    /**
     * Returns the URI for a route
     * @param string $name Name of the route
     * @param array $params GET parameters that should be added to the URI
     * @return string Route
     */
    public static function generate(string $name, array $params = [], bool $withHostUrl = false): string {
        $urlPrefix = $withHostUrl ? rtrim(Config::$PROJECT_SETTINGS["PROJECT_URL"], "/") : "";

        foreach(self::$routes as $method => $routes) {
            foreach($routes as $route => $routeData) {
                if($routeData["name"] == $name) {
                    // Found the Route
                    $requiredParams = array_keys($routeData["params"]);
                    foreach($params as $paramName => $paramValue) {
                        if(isset($routeData["params"][$paramName])) {
                            if($routeData["params"][$paramName] == "b" && is_bool($paramValue)) {
                                $paramValue = $paramValue ? "true" : "false";
                                $route = str_replace("{" . $routeData["params"][$paramName] . ":" . $paramName . "}", $paramValue, $route);
                                $requiredParams = array_diff($requiredParams, [$paramName]);
                            } else if($routeData["params"][$paramName] == "d" && ($paramValue instanceof DateTime || DateTime::createFromFormat(Config::$DATETIME_SETTINGS["DATE_TECHNICAL"], $paramValue) !== false)) {
                                if($paramValue instanceof DateTime) {
                                    $paramValue = DateFormatter::technicalDate($paramValue);
                                }

                                $route = str_replace("{" . $routeData["params"][$paramName] . ":" . $paramName . "}", $paramValue, $route);
                                $requiredParams = array_diff($requiredParams, [$paramName]);
                            } else if($routeData["params"][$paramName] == "f" && is_float($paramValue)) {
                                $paramValue = floatval($paramValue);
                                $route = str_replace("{" . $routeData["params"][$paramName] . ":" . $paramName . "}", $paramValue, $route);
                                $requiredParams = array_diff($requiredParams, [$paramName]);
                            } else if($routeData["params"][$paramName] == "i" && is_int($paramValue)) {
                                $paramValue = intval($paramValue);
                                $route = str_replace("{" . $routeData["params"][$paramName] . ":" . $paramName . "}", $paramValue, $route);
                                $requiredParams = array_diff($requiredParams, [$paramName]);
                            } else if($routeData["params"][$paramName] == "s" && is_string($paramValue)) {
                                $paramValue = urlencode(strval($paramValue));
                                $route = str_replace("{" . $routeData["params"][$paramName] . ":" . $paramName . "}", $paramValue, $route);
                                $requiredParams = array_diff($requiredParams, [$paramName]);
                            }
                        }
                    }

                    if(sizeof($requiredParams) == 0) {
                        return $urlPrefix . Config::$ROUTER_SETTINGS["ROUTER_BASE_URI"] . ltrim($route, "/");
                    }
                }
            }
        }

        return $urlPrefix . Config::$ROUTER_SETTINGS["ROUTER_BASE_URI"];
    }

    /**
     * Redirects to the file that is registered for the requested route
     * This method also sets values in the $_GET Array
     * If no route is found or the file does not exist, the 404 page will be opened
     * If the required parameters are not valid, the 400 page will be opened
     * @return void
     */
    public function startRouter(): void {
        $method = $_SERVER["REQUEST_METHOD"];
        $uri = $_SERVER["REQUEST_URI"];

        // Remove GET parameters after a question mark
        // GET parameters are set differently
        $uri = explode("?", $uri)[0];
        // Remove the root directory from the URI
        // This is required if the project is not located in the server's root directory
        if(str_starts_with($uri, Config::$ROUTER_SETTINGS["ROUTER_BASE_URI"])) {
            $uri = substr($uri, strlen(Config::$ROUTER_SETTINGS["ROUTER_BASE_URI"]));
        }
        // Remove leading and trailing slashes
        $uri = trim($uri, "/");

        $foundRoute = [];
        $routeFound = false;
        foreach(self::$routes[$method] as $routeData) {
            $route = $routeData["route"];
            $route = trim($route, "/");
            $regex = "";
            $routeParts = explode("/", $route);
            // Loop over all parts of the route and create a regex
            foreach($routeParts as $part) {
                if(preg_match("/\{([bdfis]:[a-zA-Z0-9]+)\}/", $part)) {
                    // The current route part is a parameter
                    // Add regex for the corresponding parameter type
                    $part = trim($part, "{}");
                    $paramType = explode(":", $part)[0];
                    switch($paramType) {
                        case "b":
                            $regex .= "true|false\/";
                            break;
                        case "d":
                            $regex .= DateFormatter::technicalDateRegex() . "\/";
                            break;
                        case "f":
                            $regex .= "[\d]+(\.[\d]+)?\/";
                            break;
                        case "i":
                            $regex .= "[\d]+\/";
                            break;
                        case "s":
                            $regex .= ".+\/";
                            break;
                    }
                } else {
                    // The current route part is no parameter
                    // Simply add the part to the regex
                    $regex .= $part . "\/";
                }
            }
            if(str_ends_with($regex, "\/")) {
                $regex = substr($regex, 0, strlen($regex) - 2);
            }

            if(preg_match("#^" . $regex . "$#i", $uri)) {
                // The current route matches the request
                $foundRoute = $routeData;
                $routeFound = true;
            }
        }

        if(!($routeFound)) {
            http_response_code(404);
            Comm::redirect(Router::generate("404"));
        }

        $route = $foundRoute["route"];
        $route = trim($route, "/");
        $routeTo = $foundRoute["routeTo"];

        // Set the GET parameters
        // Loop over all parts of the route
        foreach(explode("/", $route) as $key => $part) {
            if(preg_match("/\{([bdfis]:[a-zA-Z0-9]+)\}/", $part)) {
                // The current route part is a parameter
                // Retrieve the parameter type and name from the route part and the value from the URI
                $part = trim($part, "{}");
                $paramType = explode(":", $part)[0];
                $paramName = str_replace($paramType . ":", "", $part);
                $paramValue = explode("/", $uri)[$key];

                if(self::getParameterFromString($paramValue, $paramType) !== null) {
                    $paramValue = self::getParameterFromString($paramValue, $paramType);
                } else {
                    http_response_code(400);
                    Comm::redirect(Router::generate("400"));
                }

                $_GET[$paramName] = urldecode($paramValue);
            }
        }

        // Redirect to the file that is registered for the route
        if(str_ends_with($routeTo, ".php")) {
            if(file_exists($routeTo)) {
                include_once($routeTo);
            } else {
                Logger::getLogger("Router")->error("Could not find file \"{$routeTo}\" for route \"{$route}\"");
                http_response_code(404);
                Comm::redirect(self::generate("404"));
            }
        } else {
            if(file_exists($routeTo)) {
                $this->sendContentTypeHeader($routeTo);
                readfile($routeTo);
                exit;
            } else {
                Logger::getLogger("Router")->error("Could not find file \"{$routeTo}\" for route \"{$route}\"");
                http_response_code(404);
                Comm::redirect(self::generate("404"));
            }
        }
    }

    /**
     * Returns the URL that was called
     * @return string
     */
    public static function getCalledURL(): string {
        return rtrim(Config::$PROJECT_SETTINGS["PROJECT_URL"], "/") . "/" . ltrim($_SERVER["REQUEST_URI"], "/");
    }

    /**
     * Returns the import path for a file within the static directory
     * @param string $path File Path
     * @return string
     */
    public static function staticFilePath(string $path): string {
        return Config::$ROUTER_SETTINGS["ROUTER_BASE_URI"] . "static/" . trim($path, "/");
    }

    /**
     * If parsing is possible, returns the parameter of the corresponding type from a string
     * @param mixed $value Value that should be parsed
     * @param string $parameter Type of the parameter (b, d, f, i, s)
     * @return mixed|null Parsed parameter or null if parsing is not possible
     */
    private static function getParameterFromString(mixed $value, string $parameter): mixed {
        switch($parameter) {
            case "b":
                if(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== null) {
                    return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                }
                break;
            case "d":
                if(DateTime::createFromFormat(Config::$DATETIME_SETTINGS["DATE_TECHNICAL"], $value) !== false) {
                    return strval($value);
                }
                break;
            case "f":
                if(filter_var($value, FILTER_VALIDATE_FLOAT)) {
                    return floatval($value);
                }
                break;
            case "i":
                if(filter_var($value, FILTER_VALIDATE_INT)) {
                    return intval($value);
                }
                break;
            case "s":
                return urldecode(strval($value));
        }

        return null;
    }

    /**
     * Sends the correct Content-Type header for a given file
     * @param string $file File name or path
     * @return void
     */
    private function sendContentTypeHeader(string $file): void {
        $extensions = [
            "html" => "text/html",
            "css" => "text/css",
            "js" => "text/javascript",
            "json" => "application/json",
            "xml" => "application/xml",
            "png" => "image/png",
            "jpg" => "image/jpeg",
            "jpeg" => "image/jpeg",
            "gif" => "image/gif",
            "svg" => "image/svg+xml",
            "ico" => "image/x-icon",
            "ttf" => "font/ttf",
            "otf" => "font/otf",
            "woff" => "font/woff",
            "woff2" => "font/woff2",
            "eot" => "font/eot",
            "pdf" => "application/pdf",
            "zip" => "application/zip",
            "rar" => "application/x-rar-compressed",
            "7z" => "application/x-7z-compressed",
            "mp3" => "audio/mpeg",
            "wav" => "audio/wav",
            "ogg" => "audio/ogg",
            "mp4" => "video/mp4",
            "webm" => "video/webm",
            "avi" => "video/x-msvideo",
            "mpg" => "video/mpeg",
            "mpeg" => "video/mpeg",
            "flv" => "video/x-flv",
            "swf" => "application/x-shockwave-flash",
            "txt" => "text/plain",
            "csv" => "text/csv",
            "ics" => "text/calendar",
            "rtf" => "application/rtf",
            "doc" => "application/msword",
            "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "xls" => "application/vnd.ms-excel",
            "xlsx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "ppt" => "application/vnd.ms-powerpoint",
            "pptx" => "application/vnd.openxmlformats-officedocument.presentationml.presentation"
        ];

        foreach($extensions as $extension => $contentType) {
            if(str_ends_with($file, "." . $extension)) {
                header("Content-Type: $contentType");
                return;
            }
        }
    }
}
