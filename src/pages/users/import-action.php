<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::FACILITATOR, Router->generate("index"));

$post = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "group" => CommonValidators::group(false),
        "password" => CommonValidators::password(false)
    ])
    ->validate($_POST, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        exit;
    });

$fileUpload = new \struktal\FileUpload\FileUpload();
$fileUpload->setInputName("file")
    ->setMultiple(false)
    ->setAllowedMimeTypes(["text/csv"])
    ->setMaxSize(2)
    ->handleUploadedFiles();
if(!$fileUpload->successful() || empty($fileUpload->getFiles())) {
    InfoMessage->error(t("Please fill out all the required fields."));
    exit;
}
$files = $fileUpload->getFiles();

$csv = new \struktal\CSVReader\CSVReader();
$csv->setFile($fileUpload->getFiles()[0]["tmp_name"])
    ->detectDelimiter()
    ->read();
$csvData = $csv->getData();

if(count($csvData) > 50) {
    InfoMessage->error(t("The CSV file contains too many entries. A maximum of 50 users can be imported at a time."));
    exit;
}

foreach($csvData as $data) {
    if(!is_array($data) || sizeof($data) !== 2) {
        InfoMessage->error(t("The CSV file is not formatted correctly."));
        exit;
    }
}

$groupId = isset($post["group"]) ? $post["group"]->getId() : null;
$leadingCourseId = null;

if($groupId === null) {
    Logger->tag("Users")->info("User {$user->getId()} ({$user->getFullName()}) is importing users from CSV file with default group.");
} else {
    Logger->tag("Users")->info("User {$user->getId()} ({$user->getFullName()}) is importing users from CSV file with group ID {$groupId} ({$post["group"]->getName()}).");
}

$importedUsers = [];
$importedUsersPasswords = [];
foreach($csvData as $data) {
    $lastName = trim($data[0]);
    $firstName = trim($data[1]);
    $username = \app\users\UserService::generateUsername($firstName, $lastName);

    Logger->tag("Users")->trace("Importing user with name {$firstName} {$lastName} and username {$username}.");

    $account = new \app\users\User();
    $account->setUsername($username);
    $account->setEmail($username);
    $password = null;
    if(!empty($post["password"])) {
        $password = $post["password"];
    } else {
        $password = \app\users\UserService::generatePassword();
    }
    $account->setPassword($password);
    $account->setEmailVerified(true);
    $account->setPermissionLevel(\app\users\PermissionLevel::USER);
    $account->setFirstName($firstName);
    $account->setLastName($lastName);
    $account->setGroupId($groupId);
    $account->setLeadingCourseId($leadingCourseId);
    $account->setLastLogin(null);
    $account->setOneTimePassword(null);
    $account->setOneTimePasswordExpiration(null);
    \app\users\User::dao()->save($account);

    Logger->tag("Users")->trace("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) imported the user {$account->getId()} ({$account->getFullName()}).");

    $importedUsers[] = $account;
    $importedUsersPasswords[$account->getId()] = $password;
}

$userCount = count($importedUsers);

InfoMessage->success(t("\$\$count\$\$ users have been imported.", [
    "count" => $userCount
]));

if($groupId === null) {
    Logger->tag("Users")->info("User {$user->getId()} ({$user->getFullName()}) has imported {$userCount} users from CSV file with default group.");
} else {
    Logger->tag("Users")->info("User {$user->getId()} ({$user->getFullName()}) has imported {$userCount} users from CSV file with group ID {$groupId} ({$post["group"]->getName()}).");
}

header("Content-Type: application/pdf");
$pdf = new PDF($user, t("Account credentials"), "pdf.accountcredentials", [
    "accounts" => $importedUsers,
    "passwords" => $importedUsersPasswords,
    "loginQrCodeData" => QR::loginQrCode()
]);
$pdf->stream();
