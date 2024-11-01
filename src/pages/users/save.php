<?php

$user = Auth::enforceLogin(PermissionLevel::FACILITATOR->value, Router::generate("index"));

$validation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "user" => \validation\Validator::create([
            \validation\IsInDatabase::create(User::dao(), [
                "permissionLevel" => PermissionLevel::USER->value
            ])->setErrorMessage(t("The user that should be edited does not exist."))
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
        "group" => \validation\Validator::create([
            \validation\IsInDatabase::create(Group::dao())
        ]),
        "password" => \validation\Validator::create([
            \validation\NullOnEmpty::create(),
            \validation\IsString::create(),
            \validation\MinLength::create(8),
            \validation\MaxLength::create(256)
        ]),
        "leadingCourse" => \validation\Validator::create([
            \validation\NullOnEmpty::create(),
            \validation\IsInDatabase::create(Course::dao())
        ])
    ])
])->setErrorMessage(t("Please fill out all the required fields."));
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    if(isset($_POST["user"]) && !User::dao()->hasId($_POST["user"])) {
        // Comm::redirect(Router::generate("users-overview"));
    } else if(isset($_POST["user"])) {
        // Comm::redirect(Router::generate("users-edit", ["user" => $_POST["user"]]));
    } else {
        // Comm::redirect(Router::generate("users-create"));
    }
    exit;
}

$account = new User();
if(isset($post["user"])) {
    $account = $post["user"];
}

$groupId = isset($post["group"]) ? $post["group"]->getId() : null;
$leadingCourseId = isset($post["leadingCourse"]) ? $post["leadingCourse"]->getId() : null;

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
$account->setPermissionLevel(PermissionLevel::USER->value);
$account->setFirstName($post["firstName"]);
$account->setLastName($post["lastName"]);
$account->setGroupId($groupId);
$account->setLeadingCourseId($leadingCourseId);
$account->setLastLogin(null);
$account->setOneTimePassword(null);
$account->setOneTimePasswordExpiration(null);
User::dao()->save($account);

Logger::getLogger("Users")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) saved the user {$account->getId()} ({$account->getFullName()})");

new InfoMessage(t("The user has been saved."), InfoMessageType::SUCCESS);

header("Content-Type: application/pdf");
$pdf = new PDF($user, t("Account credentials"), "pdf.accountcredentials", [
    "accounts" => [$account],
    "passwords" => [$account->getId() => $password],
    "loginQrCodeData" => QR::loginQrCode()
]);
$pdf->stream();
