<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

if(isset($_POST["userId"]) && is_numeric($_POST["userId"])) {
    $userId = intval($_POST["userId"]);
    $account = User::dao()->getObject(["id" => $userId, "permissionLevel" => PermissionLevel::FACILITATOR->value]);
    if(!$account instanceof User) {
        new InfoMessage(t("The facilitator that should be edited does not exist."), InfoMessageType::ERROR);
        Comm::redirect(Router::generate("facilitators-overview"));
    }
} else {
    $account = new User();
}

if(empty($_POST["firstName"]) || empty($_POST["lastName"])) {
    new InfoMessage(t("Please fill out all the required fields."), InfoMessageType::ERROR);
    if(isset($userId)) {
        Comm::redirect(Router::generate("facilitators-edit", ["userId" => $userId]));
    } else {
        Comm::redirect(Router::generate("facilitators-create"));
    }
}

if($account->getUsername() === "") {
    $username = User::dao()->generateUsername($_POST["firstName"], $_POST["lastName"]);
    $account->setUsername($username);
    $account->setEmail($username);
}
if(!empty($_POST["password"])) {
    $account->setPassword($_POST["password"]);
} else if($account->getPassword() === "") {
    $account->setPassword(User::dao()->generatePassword());
}
$account->setEmailVerified(true);
$account->setPermissionLevel(PermissionLevel::FACILITATOR->value);
$account->setFirstName($_POST["firstName"]);
$account->setLastName($_POST["lastName"]);
$account->setGroup(null);
$account->setLeadingCourse(null);
$account->setLastLogin(null);
$account->setOneTimePassword(null);
$account->setOneTimePasswordExpiration(null);
User::dao()->save($account);

new InfoMessage(t("The facilitator has been saved."), InfoMessageType::SUCCESS);
Comm::redirect(Router::generate("facilitators-overview"));
