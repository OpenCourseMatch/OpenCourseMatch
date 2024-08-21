<?php

$user = Auth::enforceLogin(PermissionLevel::FACILITATOR->value, Router::generate("index"));

if(isset($_POST["userId"]) && is_numeric($_POST["userId"])) {
    $userId = intval($_POST["userId"]);
    $account = User::dao()->getObject(["id" => $userId, "permissionLevel" => PermissionLevel::USER->value]);
    if(!$account instanceof User) {
        new InfoMessage(t("The user that should be edited does not exist."), InfoMessageType::ERROR);
        Comm::redirect(Router::generate("users-overview"));
    }
} else {
    $account = new User();
}

if(empty($_POST["firstName"]) || empty($_POST["lastName"]) || !isset($_POST["group"]) || !is_numeric($_POST["group"])) {
    new InfoMessage(t("Please fill out all the required fields."), InfoMessageType::ERROR);
    if(isset($userId)) {
        Comm::redirect(Router::generate("users-edit", ["userId" => $userId]));
    } else {
        Comm::redirect(Router::generate("users-create"));
    }
}

if($_POST["group"] !== "0") {
    $group = Group::dao()->getObject(["id" => $_POST["group"]]);
    if(!$group instanceof Group) {
        new InfoMessage(t("The group that the user should be assigned to does not exist."), InfoMessageType::ERROR);
        if(isset($userId)) {
            Comm::redirect(Router::generate("users-edit", ["userId" => $userId]));
        } else {
            Comm::redirect(Router::generate("users-create"));
        }
    }
}

$groupId = isset($group) ? $group->getId() : null;

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
$account->setPermissionLevel(PermissionLevel::USER->value);
$account->setFirstName($_POST["firstName"]);
$account->setLastName($_POST["lastName"]);
$account->setGroup($groupId);
// TODO: Leading course
$account->setLastLogin(null);
$account->setOneTimePassword(null);
$account->setOneTimePasswordExpiration(null);
User::dao()->save($account);

new InfoMessage(t("The user has been saved."), InfoMessageType::SUCCESS);
Comm::redirect(Router::generate("users-overview"));
