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
            \validation\IsRequired::create(),
            \validation\IsString::create(),
            \validation\MinLength::create(1),
            \validation\MaxLength::create(64),
        ]),
        "lastName" => \validation\Validator::create([
            \validation\IsRequired::create(),
            \validation\IsString::create(),
            \validation\MinLength::create(1),
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
        ])
    ])
])->setErrorMessage(t("Please fill out all the required fields"));
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    if(isset($_POST["user"])) {
        Comm::redirect(Router::generate("users-edit", ["userId" => $_POST["userId"]]));
    } else {
        Comm::redirect(Router::generate("users-create"));
    }
}

$account = new User();
if(isset($post["user"])) {
    $account = $post["user"];
}

$groupId = isset($post["group"]) ? $post["group"]->getId() : null;

if($account->getUsername() === "") {
    $username = User::dao()->generateUsername($post["firstName"], $post["lastName"]);
    $account->setUsername($username);
    $account->setEmail($username);
}
if(!empty($post["password"])) {
    $account->setPassword($post["password"]);
} else if($account->getPassword() === "") {
    $account->setPassword(User::dao()->generatePassword());
}
$account->setEmailVerified(true);
$account->setPermissionLevel(PermissionLevel::USER->value);
$account->setFirstName($post["firstName"]);
$account->setLastName($post["lastName"]);
$account->setGroup($groupId);
// TODO: Leading course
$account->setLastLogin(null);
$account->setOneTimePassword(null);
$account->setOneTimePasswordExpiration(null);
User::dao()->save($account);

new InfoMessage(t("The user has been saved."), InfoMessageType::SUCCESS);
Comm::redirect(Router::generate("users-overview"));
