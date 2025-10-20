<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));

$post = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "user" => CommonValidators::user(false, [
            "permissionLevel" => \app\users\PermissionLevel::FACILITATOR->value
        ], t("The facilitator that should be edited does not exist.")),
        "firstName" => CommonValidators::name(),
        "lastName" => CommonValidators::name(),
        "password" => CommonValidators::password(false)
    ])
    ->validate($_POST, function(\struktal\validation\ValidationException $e) {
        \struktal\API\API::sendWrappedJson([
            "message" => $e->getMessage()
        ], \struktal\API\HTTPResponse::BAD_REQUEST);
    });

$account = new \app\users\User();
if(isset($post["user"])) {
    $account = $post["user"];
}

if($account->getUsername() === "") {
    $username = \app\users\UserService::generateUsername($post["firstName"], $post["lastName"]);
    $account->setUsername($username);
    $account->setEmail($username);
}
$password = null;
if(!empty($post["password"])) {
    $password = $post["password"];
} else if($account->getPassword() === "") {
    $password = \app\users\UserService::generatePassword();
}
if($password !== null) {
    $account->setPassword($password);
}
$account->setEmailVerified(true);
$account->setPermissionLevel(\app\users\PermissionLevel::FACILITATOR);
$account->setFirstName($post["firstName"]);
$account->setLastName($post["lastName"]);
$account->setGroupId(null);
$account->setLeadingCourseId(null);
$account->setLastLogin(null);
$account->setOneTimePassword(null);
$account->setOneTimePasswordExpiration(null);
\app\users\User::dao()->save($account);

Logger->tag("Facilitators")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) saved the facilitator {$account->getId()} ({$account->getFullName()})");

InfoMessage->success(t("The facilitator has been saved."));

header("Content-Type: application/pdf");
$pdf = new PDF($user, t("Account credentials"), "pdf.accountcredentials", [
    "accounts" => [$account],
    "passwords" => [$account->getId() => $password],
    "loginQrCodeData" => QR::loginQrCode()
]);
$pdf->stream();
