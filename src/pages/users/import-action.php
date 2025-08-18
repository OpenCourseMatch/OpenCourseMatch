<?php

$user = Auth->enforceLogin(PermissionLevel::FACILITATOR->value, Router->generate("index"));

$validation = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "group" => CommonValidators::group(false),
        "password" => CommonValidators::password(false)
    ])
    ->build();
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\struktal\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    exit;
}

$fileUpload = new \struktal\FileUpload\FileUpload();
$fileUpload->setInputName("file")
    ->setMultiple(false)
    ->setAllowedMimeTypes(["text/csv"])
    ->setMaxSize(2)
    ->handleUploadedFiles();
if(!$fileUpload->successful() || empty($fileUpload->getFiles())) {
    new InfoMessage(t("Please fill out all the required fields."), InfoMessageType::ERROR);
    exit;
}
$files = $fileUpload->getFiles();

$csv = new \struktal\CSVReader\CSVReader();
$csv->setFile($fileUpload->getFiles()[0]["tmp_name"])
    ->detectDelimiter()
    ->read();
$csvData = $csv->getData();

if(count($csvData) > 50) {
    new InfoMessage(t("The CSV file contains too many entries. A maximum of 50 users can be imported at a time."), InfoMessageType::ERROR);
    exit;
}

foreach($csvData as $data) {
    if(!is_array($data) || sizeof($data) !== 2) {
        new InfoMessage(t("The CSV file is not formatted correctly."), InfoMessageType::ERROR);
        exit;
    }
}

$groupId = isset($post["group"]) ? $post["group"]->getId() : null;
$leadingCourseId = null;

if($groupId === null) {
    Logger::getLogger("Users")->info("User {$user->getId()} ({$user->getFullName()}) is importing users from CSV file with default group.");
} else {
    Logger::getLogger("Users")->info("User {$user->getId()} ({$user->getFullName()}) is importing users from CSV file with group ID {$groupId} ({$post["group"]->getName()}).");
}

$importedUsers = [];
$importedUsersPasswords = [];
foreach($csvData as $data) {
    $lastName = trim($data[0]);
    $firstName = trim($data[1]);
    $username = User::dao()->generateUsername($firstName, $lastName);

    Logger::getLogger("Users")->trace("Importing user with name {$firstName} {$lastName} and username {$username}.");

    $account = new User();
    $account->setUsername($username);
    $account->setEmail($username);
    $password = null;
    if(!empty($post["password"])) {
        $password = $post["password"];
    } else {
        $password = User::dao()->generatePassword();
    }
    $account->setPassword($password);
    $account->setEmailVerified(true);
    $account->setPermissionLevel(PermissionLevel::USER->value);
    $account->setFirstName($firstName);
    $account->setLastName($lastName);
    $account->setGroupId($groupId);
    $account->setLeadingCourseId($leadingCourseId);
    $account->setLastLogin(null);
    $account->setOneTimePassword(null);
    $account->setOneTimePasswordExpiration(null);
    User::dao()->save($account);

    Logger::getLogger("Users")->trace("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) imported the user {$account->getId()} ({$account->getFullName()}).");

    $importedUsers[] = $account;
    $importedUsersPasswords[$account->getId()] = $password;
}

$userCount = count($importedUsers);

new InfoMessage(t("\$\$count\$\$ users have been imported.", [
    "count" => $userCount
]), InfoMessageType::SUCCESS);

if($groupId === null) {
    Logger::getLogger("Users")->info("User {$user->getId()} ({$user->getFullName()}) has imported {$userCount} users from CSV file with default group.");
} else {
    Logger::getLogger("Users")->info("User {$user->getId()} ({$user->getFullName()}) has imported {$userCount} users from CSV file with group ID {$groupId} ({$post["group"]->getName()}).");
}

header("Content-Type: application/pdf");
$pdf = new PDF($user, t("Account credentials"), "pdf.accountcredentials", [
    "accounts" => $importedUsers,
    "passwords" => $importedUsersPasswords,
    "loginQrCodeData" => QR::loginQrCode()
]);
$pdf->stream();
