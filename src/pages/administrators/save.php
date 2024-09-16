<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

$validation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "user" => \validation\Validator::create([
            \validation\IsInDatabase::create(User::dao(), [
                "permissionLevel" => PermissionLevel::ADMIN->value
            ])->setErrorMessage(t("The administrator that should be edited does not exist."))
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
        "password" => \validation\Validator::create([
            \validation\NullOnEmpty::create(),
            \validation\IsString::create(),
            \validation\MinLength::create(8),
            \validation\MaxLength::create(256)
        ])
    ])
])->setErrorMessage(t("Please fill out all the required fields."));
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    if(isset($_POST["user"]) && !isset($post["user"])) {
        Comm::redirect(Router::generate("administrators-overview"));
    } else if(isset($_POST["user"])) {
        Comm::redirect(Router::generate("administrators-edit", ["user" => $_POST["user"]]));
    } else {
        Comm::redirect(Router::generate("administrators-create"));
    }
}

$account = new User();
if(isset($post["user"])) {
    $account = $post["user"];
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
$account->setPermissionLevel(PermissionLevel::ADMIN->value);
$account->setFirstName($_POST["firstName"]);
$account->setLastName($_POST["lastName"]);
$account->setGroup(null);
$account->setLeadingCourse(null);
$account->setLastLogin(null);
$account->setOneTimePassword(null);
$account->setOneTimePasswordExpiration(null);
User::dao()->save($account);

new InfoMessage(t("The administrator has been saved."), InfoMessageType::SUCCESS);
Comm::redirect(Router::generate("administrators-overview"));
