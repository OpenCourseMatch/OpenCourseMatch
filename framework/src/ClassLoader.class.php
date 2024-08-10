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
     * Loads a single given class
     * Note that only class in files with the ending ".class.php" will be loaded
     * @param string $absolutePath
     * @return bool
     */
    public function loadClass(string $absolutePath): bool {
        if(str_ends_with($absolutePath, ".class.php")) {
            $this->load($absolutePath);
            return true;
        }

        return false;
    }

    /**
     * Loads all classes in a given directory and it's subdirectories (recursively) except those specified in $exceptions
     * Note that only classes in files with the ending ".class.php" will be loaded
     * @param string $absolutePath
     * @param array  $exceptions
     * @return void
     */
    public function loadClasses(string $absolutePath, array $exceptions = []): void {
        if(is_dir($absolutePath)) {
            $files = scandir($absolutePath);

            foreach($files as $file) {
                if(!(is_dir($absolutePath . (str_ends_with($absolutePath, "/") ? "" : "/") . $file))) {
                    if(str_ends_with($file, ".class.php") && !(in_array($file, $exceptions))) {
                        $this->loadClass($absolutePath . (str_ends_with($absolutePath, "/") ? "" : "/") . $file);
                    }
                } else if($file !== "." && $file !== "..") {
                    $this->loadClasses($absolutePath . (str_ends_with($absolutePath, "/") ? "" : "/") . $file, $exceptions);
                }
            }
        } else {
            Logger::getLogger("ClassLoader")->error("Directory {$absolutePath} does not exist");
        }
    }

    /**
     * Loads a single given enum
     * Note that only enums in files with the ending ".enum.php" will be loaded
     * @param string $absolutePath
     * @return bool
     */
    public function loadEnum(string $absolutePath): bool {
        if(str_ends_with($absolutePath, ".enum.php")) {
            $this->load($absolutePath);
            return true;
        }

        return false;
    }

    /**
     * Loads all enums in a given directory and it's subdirectories (recursively) except those specified in $exceptions
     * Note that only enums in files with the ending ".enum.php" will be loaded
     * @param string $absolutePath
     * @param array  $exceptions
     * @return void
     */
    public function loadEnums(string $absolutePath, array $exceptions = []): void {
        if(is_dir($absolutePath)) {
            $files = scandir($absolutePath);

            foreach($files as $file) {
                if(!(is_dir($absolutePath . (str_ends_with($absolutePath, "/") ? "" : "/") . $file))) {
                    if(str_ends_with($file, ".enum.php") && !(in_array($file, $exceptions))) {
                        $this->loadEnum($absolutePath . (str_ends_with($absolutePath, "/") ? "" : "/") . $file);
                    }
                } else if($file !== "." && $file !== "..") {
                    $this->loadEnums($absolutePath . (str_ends_with($absolutePath, "/") ? "" : "/") . $file, $exceptions);
                }
            }
        } else {
            Logger::getLogger("ClassLoader")->error("Directory {$absolutePath} does not exist");
        }
    }
}
