<?php

$user = Auth::enforceLogin(PermissionLevel::FACILITATOR->value, Router::generate("index"));

$validation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "group" => \validation\Validator::create([
            \validation\IsInDatabase::create(Group::dao())
        ]),
        "password" => \validation\Validator::create([
            \validation\NullOnEmpty::create(),
            \validation\IsString::create(),
            \validation\MinLength::create(8),
            \validation\MaxLength::create(256)
        ]),
    ])
])->setErrorMessage(t("Please fill out all the required fields."));
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("users-import"));
}

$uploadHelper = new \jensostertag\UploadHelper\UploadHelper();
$uploadHelper->setInputName("file")
    ->setMultiple(false)
    ->setAllowedMimeTypes(["text/csv"])
    ->setMaxSize(2)
    ->handleUploadedFiles();
if(!$uploadHelper->successful() || empty($uploadHelper->getFiles())) {
    new InfoMessage(t("Please fill out all the required fields."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("users-import"));
}
$files = $uploadHelper->getFiles();

$csv = new \jensostertag\CSVReader\CSVReader();
$csv->setFile($uploadHelper->getFiles()[0]["tmp_name"])
    ->detectDelimiter()
    ->read();
$csvData = $csv->getData();

// TODO: Limit data

foreach($csvData as $data) {
    if(!is_array($data) || sizeof($data) !== 2) {
        new InfoMessage(t("The CSV file is not formatted correctly."), InfoMessageType::ERROR);
        Comm::redirect(Router::generate("users-import"));
    }
}

$groupId = isset($post["group"]) ? $post["group"]->getId() : null;
$leadingCourseId = null;

$importedUsers = [];
$importedUsersPasswords = [];
foreach($csvData as $data) {
    $lastName = trim($data[0]);
    $firstName = trim($data[1]);
    $username = User::dao()->generateUsername($firstName, $lastName);

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
    $account->setGroup($groupId);
    $account->setLeadingCourse($leadingCourseId);
    $account->setLastLogin(null);
    $account->setOneTimePassword(null);
    $account->setOneTimePasswordExpiration(null);
    User::dao()->save($account);

    $importedUsers[] = $account;
    $importedUsersPasswords[$account->getId()] = $password;
}

new InfoMessage(t("\$\$count\$\$ users have been imported.", [
    "count" => count($importedUsers)
]), InfoMessageType::SUCCESS);

header("Content-Type: application/pdf");
$pdf = new PDF($user, t("Account credentials"), "pdf.accountcredentials", [
    "accounts" => $importedUsers,
    "passwords" => $importedUsersPasswords,
    "loginQrCodeData" => QR::loginQrCode()
]);
$pdf->stream();
