<?php

$user = Auth::enforceLogin(PermissionLevel::FACILITATOR->value, Router::generate("index"));

$validation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "group" => \validation\Validator::create([
            \validation\IsRequired::create(),
            \validation\IsInDatabase::create(Group::dao())
        ])->setErrorMessage(t("The group of which the users should be deleted does not exist."))
    ])
])->setErrorMessage(t("Please fill out all the required fields."));
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\validation\ValidationException $e) {
    var_dump($e);
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
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
} else {
    $oldGroup = $post["group"] ? $post["group"]->getId() : "DEFAULT";
    Logger::getLogger("GroupActions")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) is deleting all users of the group {$oldGroup}");
}

foreach($accounts as $account) {
    $account->preDelete();
    User::dao()->delete($account);

    Logger::getLogger("GroupActions")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the user {$account->getId()} ({$account->getFullName()})");
}

new InfoMessage(t("All users of the selected group have been deleted."), InfoMessageType::SUCCESS);
