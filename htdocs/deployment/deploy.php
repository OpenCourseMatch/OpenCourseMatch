<?php

// Read deploy-config.json file
$config = json_decode(file_get_contents("deploy-config.json"), true);

define("PROJECT_NAME", $config["projectName"]);
define("MAIL_LOGS_TO", $config["mailLogsTo"]);

header("Content-Type: text/plain");

function getTimestamp(): string {
    $dateTime = new DateTime();
    return $dateTime->format("Y/m/d  H-i-s");
}

function ipInRange($ipAddress, $addressRange): bool {
    $subnet = explode("/", $addressRange)[0];
    $mask = intval(explode("/", $addressRange)[1]);
    $isIpV6 = str_contains($ipAddress, ":");

    if($isIpV6 !== str_contains($subnet, ":")) {
        return false;
    }

    if($isIpV6) {
        $ipAddress = inet_pton($ipAddress);
        $subnet = inet_pton($subnet);

        $maskBinary = str_repeat("f", $mask / 4);
        switch($mask % 4) {
            case 1:
                $maskBinary .= "8";
                break;
            case 2:
                $maskBinary .= "c";
                break;
            case 3:
                $maskBinary .= "e";
                break;
        }

        $maskBinary = str_pad($maskBinary, 32, "0");
        $maskBinary = pack("H*", $maskBinary);
        return ($ipAddress & $maskBinary) === $subnet;
    } else {
        if($mask <= 0) {
            return false;
        }

        $ipAddressBinary = sprintf("%032b", ip2long($ipAddress));
        $subnetBinary = sprintf("%032b", ip2long($subnet));
        return (substr_compare($ipAddressBinary, $subnetBinary, 0, $mask) === 0);
    }
}

$shortLog = "";
$detailedLog = "";

function sendLog(string $shortLog, string $detailedLog, bool $successful): void {
    $shortLog .= PHP_EOL . "Deployment " . ($successful ? "finished" : "failed") . " at " . getTimestamp() . PHP_EOL;
    $detailedLog .= PHP_EOL . "Deployment " . ($successful ? "finished" : "failed") . " at " . getTimestamp() . PHP_EOL;

    // Send mails
    foreach(MAIL_LOGS_TO as $mail) {
        $subject = ($successful ? "[SUCCESS] " : "[FAILURE] ") . " Deployment of " . PROJECT_NAME;
        mail($mail, $subject, $detailedLog);
    }

    // Print log
    echo $shortLog;

    exit;
}

$header = "";
$header .= "##################################################" . PHP_EOL;
$header .= "#                   DEPLOYMENT                   #" . PHP_EOL;
$header .= "#              " . getTimestamp() . "              #" . PHP_EOL;
$header .= "##################################################" . PHP_EOL;
$header .= PHP_EOL;

$shortLog .= $header;
$detailedLog .= $header;

// Check allowed IP addresses
$shortLog .= "Checking access permission" . PHP_EOL;
$detailedLog .= "Checking access permission" . PHP_EOL;

// Retrieve allowed IPs from the GitHub API
$detailedLog .= "Retrieving allowed IPs from GitHub API (https://api.github.com/meta)" . PHP_EOL;

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://api.github.com/meta");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_USERAGENT, "PHP Deployment Script");
$response = curl_exec($curl);
curl_close($curl);

if($response === false) {
    http_response_code(500);
    $shortLog = "Failed to retrieve allowed IPs from GitHub API" . PHP_EOL;
    $detailedLog .= "Failed to retrieve allowed IPs from GitHub API" . PHP_EOL;
    sendLog($shortLog, $detailedLog, false);
}

$jsonResponse = json_decode($response, true);
$allowedIps = $jsonResponse["actions"];

$ip = $_SERVER["REMOTE_ADDR"];
$detailedLog .= "Accessed from IP " . $ip . PHP_EOL;

$allowed = false;
foreach($allowedIps as $allowedIp) {
    if(ipInRange($ip, $allowedIp)) {
        $allowed = true;
        break;
    }
}

if(!$allowed) {
    http_response_code(403);
    $shortLog .= "You are not allowed to access this file" . PHP_EOL;
    $detailedLog .= "IP " . $ip . " is not allowed to access the deployment script" . PHP_EOL;
    sendLog($shortLog, $detailedLog, false);
}

$shortLog .= "Access granted" . PHP_EOL;
$shortLog .= PHP_EOL;
$detailedLog .= "Access granted" . PHP_EOL;
$detailedLog .= PHP_EOL;

// Commands
$commands = [
    "whoami",
    "git pull",
    "cd ../.. && composer build"
];

$detailedLog .= "Running commands " . json_encode($commands) . PHP_EOL;
$detailedLog .= PHP_EOL;

// Run commands with exec
foreach($commands as $command) {
    $shortLog .= "Running command: " . $command . PHP_EOL;
    $detailedLog .= "Running command: " . $command . PHP_EOL;

    $output = [];
    $resultCode = 0;
    exec($command . " 2>&1", $output, $resultCode);
    foreach($output as $line) {
        $detailedLog .= $line . PHP_EOL;
    }

    $shortLog .= "Finished with exit code " . $resultCode . PHP_EOL;
    $detailedLog .= "Finished with exit code " . $resultCode . PHP_EOL;

    if($resultCode !== 0) {
        http_response_code(500);
        $shortLog .= "Deployment failed at command " . $command . PHP_EOL;
        $detailedLog .= "Deployment failed at command " . $command . PHP_EOL;
        sendLog($shortLog, $detailedLog, false);
    }

    $shortLog .= PHP_EOL;
    $detailedLog .= PHP_EOL;
}

sendLog($shortLog, $detailedLog, true);
