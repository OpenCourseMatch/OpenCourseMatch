<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

$validation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "user" => \validation\Validator::create([
            \validation\IsInDatabase::create(User::dao(), [
                "permissionLevel" => PermissionLevel::ADMIN->value
            ])->setErrorMessage(t("The administrator that should be edited does not exist."))
        ]),
        "firstName" => \validation\Validator::create([
            \validation\IsRequired::create(true),
            \validation\IsString::create(),
            \validation\MaxLength::create(64),
        ]),
        "lastName" => \validation\Validator::create([
            \validation\IsRequired::create(true),
            \validation\IsString::create(),
            \validation\MaxLength::create(64),
        ]),
        "password" => \validation\Validator::create([
            \validation\NullOnEmpty::create(),
            \validation\IsString::create(),
            \validation\MinLength::create(8),
            \validation\MaxLength::create(256)
        ])
    ])
])->setErrorMessage(t("Please fill out all the required fields."));
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\validation\ValidationException $e) {
    Comm::apiSendJson(HTTPResponses::$RESPONSE_BAD_REQUEST, [
        "message" => $e->getMessage()
    ]);
}

$account = new User();
if(isset($post["user"])) {
    $account = $post["user"];
}

if($account->getUsername() === "") {
    $username = User::dao()->generateUsername($post["firstName"], $post["lastName"]);
    $account->setUsername($username);
    $account->setEmail($username);
}
$password = null;
if(!empty($post["password"])) {
    $password = $post["password"];
} else if($account->getPassword() === "") {
    $password = User::dao()->generatePassword();
}
if($password !== null) {
    $account->setPassword($password);
}
$account->setEmailVerified(true);
$account->setPermissionLevel(PermissionLevel::ADMIN->value);
$account->setFirstName($post["firstName"]);
$account->setLastName($post["lastName"]);
$account->setGroupId(null);
$account->setLeadingCourseId(null);
$account->setLastLogin(null);
$account->setOneTimePassword(null);
$account->setOneTimePasswordExpiration(null);
User::dao()->save($account);

Logger::getLogger("Administrators")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) saved the administrator {$account->getId()} ({$account->getFullName()})");

new InfoMessage(t("The administrator has been saved."), InfoMessageType::SUCCESS);

header("Content-Type: application/pdf");
$pdf = new PDF($user, t("Account credentials"), "pdf.accountcredentials", [
    "accounts" => [$account],
    "passwords" => [$account->getId() => $password],
    "loginQrCodeData" => QR::loginQrCode()
]);
$pdf->stream();
