<?php

class Translator {
    const TRANSLATIONS_PATH = __APP_DIR__ . "/project/translations";

    private static string $domain = "messages";
    private static string $locale = "en_US";
    /** @var resource|null $translationFile */
    private static $translationFile = null;

    public static function setDomain(string $domain): void {
        self::$domain = $domain;
        self::openTranslationFile();
    }

    public static function setLocale(string $locale): void {
        self::$locale = $locale;
        self::openTranslationFile();
    }

    private static function openTranslationFile(): void {
        if(self::$translationFile && is_resource(self::$translationFile)) {
            fclose(self::$translationFile);
        }

        try {
            self::$translationFile = fopen(self::TRANSLATIONS_PATH . "/" . self::$locale . "/" . self::$domain . ".json", "r");
        } catch(Exception $e) {
            self::$translationFile = null;
        }
    }

    public static function translate(string $message, array $variables = []): string {
        if(!apcu_exists(self::$locale . "-" . self::$domain)) {
            // Read translations from file
            if(self::$translationFile && is_resource(self::$translationFile)) {
                $translations = fread(self::$translationFile, filesize(self::TRANSLATIONS_PATH . "/" . self::$locale . "/" . self::$domain . ".json"));
                $translations = json_decode($translations, true);

                // Store translations in cache
                apcu_store(self::$locale . "-" . self::$domain, $translations);

                if(isset($translations[$message])) {
                    $message = $translations[$message];
                }

                fseek(self::$translationFile, 0);
            }
        } else {
            // Read translations from cache
            $translations = apcu_fetch(self::$locale . "-" . self::$domain);
            if(isset($translations[$message])) {
                $message = $translations[$message];
            }
        }

        // Replace the variables in the message
        $messageParts = explode("$$", $message);
        if(count($messageParts) > 1) {
            $message = $messageParts[0];
            for($i = 1; $i < count($messageParts); $i++) {
                $messagePart = $messageParts[$i];
                if($i % 2 === 0) {
                    $message .= $messagePart;
                    continue;
                }

                if(array_key_exists($messagePart, $variables)) {
                    $message .= $variables[$messagePart];
                } else {
                    $message .= "$$" . $messagePart;
                    if($i < count($messageParts) - 1) {
                        $message .= "$$";
                    }
                }
            }
        }

        return $message;
    }

    /**
     * Get a sorted list of preferred locales from the Accept-Language header
     * @return array
     */
    public static function getPreferredLocalesFromHeader(): array {
        $header = $_SERVER["HTTP_ACCEPT_LANGUAGE"] ?? "";
        $header = trim($header);

        // Match regex against header to extract the language parts
        $regex = "/[a-zA-Z]{1,8}(?:-[a-zA-Z]{1,8}){0,2}(?:;q=[0-9]+(?:\.[0-9]+)?)?/";
        preg_match_all($regex, $header, $headerParts);
        $headerParts = $headerParts[0];

        // Interpret the language parts
        $preferredLocales = array_map(function(string $part) {
            if(!$part) {
                return null;
            }

            $bits = explode(";", $part);
            $localeTag = explode("-", $bits[0]);
            $hasRegion = count($localeTag) >= 2;
            $hasScript = count($localeTag) === 3;

            return [
                "code" => $bits[0],
                "priority" => count($bits) > 1 ? floatval(explode("=", $bits[1])[1]) : 1.0,
                "language" => $localeTag[0],
                "script" => $hasScript ? $localeTag[1] : null,
                "region" => $hasRegion ? $localeTag[$hasScript ? 2 : 1] : null
            ];
        }, $headerParts);

        // Filter out null values
        $preferredLocales = array_filter($preferredLocales, function($part) {
            return $part !== null;
        });

        // Sort locales by their priority
        usort($preferredLocales, function($a, $b) {
            return $b["priority"] <=> $a["priority"];
        });

        return $preferredLocales;
    }

    /**
     * Returns a list of available locales
     * @return array
     */
    public static function getAvailableLocales(): array {
        $directories = scandir(self::TRANSLATIONS_PATH);
        return array_filter($directories, function($directory) {
            return is_dir(self::TRANSLATIONS_PATH . "/" . $directory) && $directory !== "." && $directory !== "..";
        });
    }
}
