<?php

$user = Auth::enforceLogin(PermissionLevel::FACILITATOR->value, Router::generate("index"));

$validation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "group" => \validation\Validator::create([
            \validation\IsRequired::create(),
            \validation\IsInDatabase::create(Group::dao())->setErrorMessage(t("The group that should be edited does not exist."))
        ]),
        "resetPassword" => \validation\Validator::create([
            \validation\NullOnEmpty::create()
        ]),
        "newPassword" => \validation\Validator::create([
            \validation\NullOnEmpty::create(),
            \validation\IsString::create(),
            \validation\MinLength::create(8),
            \validation\MaxLength::create(256)
        ]),
        "changeGroup" => \validation\Validator::create([
            \validation\NullOnEmpty::create()
        ]),
        "newGroup" => \validation\Validator::create([
            \validation\NullOnEmpty::create(),
            \validation\IsInDatabase::create(Group::dao())
        ])
    ])
])->setErrorMessage(t("Please fill out all the required fields."));
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    exit;
}

if($post["resetPassword"] === null && $post["changeGroup"] === null) {
    new InfoMessage(t("No actions were selected. No user data has been modified."), InfoMessageType::WARNING);
    exit;
}

$group = $post["group"];
$accounts = User::dao()->getObjects([
    "groupId" => $group?->getId(),
    "permissionLevel" => PermissionLevel::USER->value
]);

if(empty($accounts)) {
    new InfoMessage(t("No users were found in the selected group. The actions have not been executed."), InfoMessageType::WARNING);
    exit;
}

if($post["resetPassword"] === "1") {
    $oldGroup = $post["group"] ? $post["group"]->getId() : "DEFAULT";
    Logger::getLogger("GroupActions")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) is resetting the password for all users of the group {$oldGroup}");
}

if($post["changeGroup"] === "1") {
    $oldGroup = $post["group"] ? $post["group"]->getId() : "DEFAULT";
    $newGroup = $post["newGroup"] ? $post["newGroup"]->getId() : "DEFAULT";
    Logger::getLogger("GroupActions")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) is changing the group for all users of the group {$oldGroup} to {$newGroup}");
}

$passwords = [];

foreach($accounts as $account) {
    $edited = false;

    if($post["resetPassword"] === "1") {
        $password = User::dao()->generatePassword();
        if($post["newPassword"] !== null) {
            $password = $post["newPassword"];
        }

        $account->setPassword($password);
        $passwords[$account->getId()] = $password;

        $edited = true;
    } else {
        $passwords[$account->getId()] = null;
    }

    if($post["changeGroup"] === "1") {
        if($post["newGroup"] === null) {
            $account->setGroupId(null);
        } else {
            $account->setGroupId($post["newGroup"]->getId());
        }

        $edited = true;
    }

    if($edited) {
        User::dao()->save($account);
        Logger::getLogger("GroupActions")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) saved the user {$account->getId()} ({$account->getFullName()})");
    }
}

new InfoMessage(t("The actions have been executed for all users of the selected group."), InfoMessageType::SUCCESS);

header("Content-Type: application/pdf");
$pdf = new PDF($user, t("Account credentials"), "pdf.accountcredentials", [
    "accounts" => $accounts,
    "passwords" => $passwords,
    "loginQrCodeData" => QR::loginQrCode()
]);
$pdf->stream();
