<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::FACILITATOR, Router->generate("index"));

$validation = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "user" => CommonValidators::user(false, [
            "permissionLevel" => \app\users\PermissionLevel::USER
        ], t("The user that should be edited does not exist.")),
        "firstName" => CommonValidators::name(),
        "lastName" => CommonValidators::name(),
        "group" => CommonValidators::group(false),
        "password" => CommonValidators::password(false),
        "leadingCourse" => CommonValidators::course(false)
    ])
    ->build();
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\struktal\validation\ValidationException $e) {
    \struktal\API\API::sendWrappedJson([
        "message" => $e->getMessage()
    ], \struktal\API\HTTPResponse::BAD_REQUEST);
}

$account = new \app\users\User();
if(isset($post["user"])) {
    $account = $post["user"];
}

$groupId = isset($post["group"]) ? $post["group"]->getId() : null;
$leadingCourseId = isset($post["leadingCourse"]) ? $post["leadingCourse"]->getId() : null;

if($account->getUsername() === "") {
    $username = \app\users\User::dao()->generateUsername($post["firstName"], $post["lastName"]);
    $account->setUsername($username);
    $account->setEmail($username);
}
$password = null;
if(!empty($post["password"])) {
    $password = $post["password"];
} else if($account->getPassword() === "") {
    $password = \app\users\User::dao()->generatePassword();
}
if($password !== null) {
    $account->setPassword($password);
}
$account->setEmailVerified(true);
$account->setPermissionLevel(\app\users\PermissionLevel::USER);
$account->setFirstName($post["firstName"]);
$account->setLastName($post["lastName"]);
$account->setGroupId($groupId);
$account->setLeadingCourseId($leadingCourseId);
$account->setShowHelpBoxes(true);
$account->setLastLogin(null);
$account->setOneTimePassword(null);
$account->setOneTimePasswordExpiration(null);
\app\users\User::dao()->save($account);

Logger->tag("Users")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()->name}) saved the user {$account->getId()} ({$account->getFullName()})");

InfoMessage->success(t("The user has been saved."));

header("Content-Type: application/pdf");
$pdf = new PDF($user, t("Account credentials"), "pdf.accountcredentials", [
    "accounts" => [$account],
    "passwords" => [$account->getId() => $password],
    "loginQrCodeData" => QR::loginQrCode()
]);
$pdf->stream();
