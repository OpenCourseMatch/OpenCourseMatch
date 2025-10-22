<?php

class ClassLoader {
    private static ?ClassLoader $instance = null;

    private function __construct() {}

    /**
     * Returns the instance of the ClassLoader
     * @return ClassLoader
     */
    public static function getInstance(): ClassLoader {
        if(self::$instance === null) {
            self::$instance = new ClassLoader();
        }

        return self::$instance;
    }

    /**
     * Loads a single given file
     * @param string $absolutePath
     * @return void
     */
    public function load(string $absolutePath): void {
        require_once($absolutePath);
    }

    /**
     * Loads all PHP files in a given directory and it's subdirectories (recursively) except those specified in $exceptions
     * @param string $absolutePath
     * @param array  $exceptions
     * @return void
     */
    public function loadDirectory(string $absolutePath, array $exceptions = []): void {
        if(is_dir($absolutePath)) {
            $files = scandir($absolutePath);

            foreach($files as $file) {
                if(!(is_dir($absolutePath . (str_ends_with($absolutePath, "/") ? "" : "/") . $file))) {
                    if(str_ends_with($file, ".php") && !(in_array($file, $exceptions))) {
                        $this->load($absolutePath . (str_ends_with($absolutePath, "/") ? "" : "/") . $file);
                    }
                } else if($file !== "." && $file !== "..") {
                    $this->loadDirectory($absolutePath . (str_ends_with($absolutePath, "/") ? "" : "/") . $file, $exceptions);
                }
            }
        } else {
            Logger->tag("ClassLoader")->error("Directory {$absolutePath} does not exist");
        }
    }
}
