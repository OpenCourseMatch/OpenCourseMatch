<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::FACILITATOR, Router->generate("index"));

$post = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "group" => CommonValidators::group(true, [], t("The group of which the users should be deleted does not exist."))
    ])
    ->validate($_POST, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        exit;
    });

$group = $post["group"];
$accounts = \app\users\User::dao()->getObjects([
    "groupId" => $group?->getId(),
    "permissionLevel" => \app\users\PermissionLevel::USER
]);

if(empty($accounts)) {
    InfoMessage->warning(t("No users were found in the selected group. The actions have not been executed."));
    exit;
} else {
    $oldGroup = $post["group"] ? $post["group"]->getId() : "DEFAULT";
    Logger->tag("GroupActions")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) is deleting all users of the group {$oldGroup}");
}

foreach($accounts as $account) {
    $account->preDelete();
    \app\users\User::dao()->delete($account);

    Logger->tag("GroupActions")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the user {$account->getId()} ({$account->getFullName()})");
}

InfoMessage->success(t("All users of the selected group have been deleted."));
