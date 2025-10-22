<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));

$get = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "group" => CommonValidators::group(true, [], t("The group that should be deleted does not exist."))
    ])
    ->validate($_GET, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("groups-overview"));
    });

$group = $get["group"];

$group->preDelete();
\app\groups\Group::dao()->delete($group);

Logger->tag("Groups")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the group {$group->getId()} ({$group->getName()})");

InfoMessage->success(t("The group has been deleted."));
Router->redirect(Router->generate("groups-overview"));
