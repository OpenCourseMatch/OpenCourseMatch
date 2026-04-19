<?php

$distConfig = file_get_contents(__DIR__ . "/config.json.dist");

if (!$distConfig) {
    echo "Error: Unable to read config.json.dist" . PHP_EOL;
    exit(1);
}

$config = json_decode($distConfig, true);

// Update web host settings
$config["APP_URL"] = "http://localhost:3000";
$config["BASE_URI"] = "/";

// Update log settings
$config["LOG_RECIPIENTS"] = [];
$config["LOG_LEVEL"] = 6;

// Update database settings
$config["DB_HOST"] = "database";
$config["DB_USER"] = "struktal";
$config["DB_PASS"] = "struktal";
$config["DB_NAME"] = "struktal";
// Do NOT override DB_ENABLED (use default setting from dist)

// Custom overrides from arguments
foreach ($argv as $arg) {
    if (!str_starts_with($arg, "--")) {
        continue;
    }

    $parts = explode("=", substr($arg, 2), 2);
    if (count($parts) !== 2) {
        echo "Warning: Invalid argument format '$arg'. Expected --KEY=VALUE" . PHP_EOL;
        continue;
    }

    $key = $parts[0];
    $value = $parts[1];

    if (!array_key_exists($key, $config)) {
        echo "Warning: Unknown config key '$key' in argument '$arg'" . PHP_EOL;
        continue;
    }

    // Convert values to appropriate types
    if (is_numeric($value)) {
        $value = $value + 0;
    } else if (strtolower($value) === "true") {
        $value = true;
    } else if (strtolower($value) === "false") {
        $value = false;
    }

    $config[$key] = $value;
    echo "Overriding config value $key" . PHP_EOL;
}

$testingConfig = json_encode($config, JSON_PRETTY_PRINT);

if ($testingConfig === false) {
    echo "Error: Unable to encode config to JSON" . PHP_EOL;
    exit(1);
}

if (file_exists(__DIR__ . "/config.json")) {
    echo "Unable to write config.json for testing: config.json already exists." . PHP_EOL;
    echo "Writing testing config contents to STDOUT instead:" . PHP_EOL;
    echo PHP_EOL . $testingConfig . PHP_EOL;
    exit(1);
}

if (file_put_contents(__DIR__ . "/config.json", $testingConfig) === false) {
    echo "Error: Unable to write config.json" . PHP_EOL;
    exit(1);
}

echo "Successfully generated config.json for testing" . PHP_EOL;
