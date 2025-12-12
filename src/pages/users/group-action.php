<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::FACILITATOR, Router->generate("index"));

$post = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "group" => CommonValidators::group(false, [], t("The group that should be edited does not exist.")),
        "resetPassword" => CommonValidators::checkbox(),
        "newPassword" => CommonValidators::password(false),
        "changeGroup" => CommonValidators::checkbox(),
        "newGroup" => CommonValidators::group(false)
    ])
    ->validate($_POST, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        exit;
    });

if($post["resetPassword"] === null && $post["changeGroup"] === null) {
    InfoMessage->warning(t("No actions were selected. No user data has been modified."));
    exit;
}

$group = $post["group"] ?? null;
$accounts = \app\users\User::dao()->getObjects([
    "groupId" => $group?->getId(),
    "permissionLevel" => \app\users\PermissionLevel::USER
]);

if(empty($accounts)) {
    InfoMessage->warning(t("No users were found in the selected group. The actions have not been executed."));
    exit;
}

if($post["resetPassword"] === "1") {
    $oldGroup = $post["group"] ? $post["group"]->getId() : "DEFAULT";
    Logger->tag("GroupActions")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()->name}) is resetting the password for all users of the group {$oldGroup}");
}

if($post["changeGroup"] === "1") {
    $oldGroup = $post["group"] ? $post["group"]->getId() : "DEFAULT";
    $newGroup = $post["newGroup"] ? $post["newGroup"]->getId() : "DEFAULT";
    Logger->tag("GroupActions")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()->name}) is changing the group for all users of the group {$oldGroup} to {$newGroup}");
}

$passwords = [];

foreach($accounts as $account) {
    $edited = false;

    if($post["resetPassword"] === 1) {
        $password = \app\users\User::dao()->generatePassword();
        if($post["newPassword"] !== null) {
            $password = $post["newPassword"];
        }

        $account->setPassword($password);
        $passwords[$account->getId()] = $password;

        $edited = true;
    } else {
        $passwords[$account->getId()] = null;
    }

    if($post["changeGroup"] === 1) {
        if($post["newGroup"] === null) {
            $account->setGroupId(null);
        } else {
            $account->setGroupId($post["newGroup"]->getId());
        }

        $edited = true;
    }

    if($edited) {
        \app\users\User::dao()->save($account);
        Logger->tag("GroupActions")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()->name}) saved the user {$account->getId()} ({$account->getFullName()})");
    }
}

InfoMessage->success(t("The actions have been executed for all users of the selected group."));

header("Content-Type: application/pdf");
$pdf = new PDF($user, t("Account credentials"), "pdf.accountcredentials", [
    "accounts" => $accounts,
    "passwords" => $passwords,
    "loginQrCodeData" => QR::loginQrCode()
]);
$pdf->stream();
