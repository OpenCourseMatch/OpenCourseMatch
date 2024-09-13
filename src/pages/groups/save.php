<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

$validation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "group" => \validation\Validator::create([
            \validation\IsInDatabase::create(Group::dao())->setErrorMessage(t("The group that should be edited does not exist."))
        ]),
        "name" => \validation\Validator::create([
            \validation\IsRequired::create(),
            \validation\IsString::create(),
            \validation\MinLength::create(1),
            \validation\MaxLength::create(256),
        ]),
        "clearance" => \validation\Validator::create([
            \validation\IsRequired::create(),
            \validation\IsInteger::create()
        ])
    ])
])->setErrorMessage(t("Please fill out all the required fields."));
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    if(isset($_POST["group"])) {
        Comm::redirect(Router::generate("groups-edit", ["group" => $_POST["group"]]));
    } else {
        Comm::redirect(Router::generate("groups-create"));
    }
}

$group = new Group();
if(isset($post["group"])) {
    $group = $post["group"];
}

$group->setName($post["name"]);
$group->setClearance($post["clearance"]);
Group::dao()->save($group);

new InfoMessage(t("The group has been saved."), InfoMessageType::SUCCESS);
Comm::redirect(Router::generate("groups-overview"));
