<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));

$validation = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "group" => CommonValidators::group(false, [], t("The group that should be edited does not exist.")),
        "name" => Validation->create()
            ->string()
            ->minLength(1)
            ->maxLength(256)
            ->build(),
        "clearance" => Validation->create()
            ->int()
            ->build()
    ])
    ->build();
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\struktal\validation\ValidationException $e) {
    InfoMessage->error($e->getMessage());
    if(isset($_POST["group"]) && !Group::dao()->hasId($_POST["group"])) {
        Router->redirect(Router->generate("groups-overview"));
    } else if(isset($_POST["group"])) {
        Router->redirect(Router->generate("groups-edit", ["group" => $_POST["group"]]));
    } else {
        Router->redirect(Router->generate("groups-create"));
    }
}

$group = new Group();
if(isset($post["group"])) {
    $group = $post["group"];
}

$group->setName($post["name"]);
$group->setClearance($post["clearance"]);
Group::dao()->save($group);

Logger->tag("Groups")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) saved the group {$group->getId()} ({$group->getName()})");

InfoMessage->success(t("The group has been saved."));
Router->redirect(Router->generate("groups-overview"));
