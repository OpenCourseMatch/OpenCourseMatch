<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::FACILITATOR, Router->generate("index"));

$validation = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "group" => CommonValidators::group(true, [], t("The group of which the users should be deleted does not exist."))
    ])
    ->build();
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\struktal\validation\ValidationException $e) {
    InfoMessage->error($e->getMessage());
    exit;
}

$group = $post["group"];
$accounts = User::dao()->getObjects([
    "groupId" => $group?->getId(),
    "permissionLevel" => PermissionLevel::USER->value
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
    User::dao()->delete($account);

    Logger->tag("GroupActions")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the user {$account->getId()} ({$account->getFullName()})");
}

InfoMessage->success(t("All users of the selected group have been deleted."));
