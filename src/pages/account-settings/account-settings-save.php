<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::USER, Router->generate("index"));

$post = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "showHelpBoxes" => CommonValidators::checkbox()
    ])
    ->validate($_POST, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("account-settings"));
    });

$user->setShowHelpBoxes(isset($post["showHelpBoxes"]));
\app\users\User::dao()->save($user);

Logger->tag("AccountSettings")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()->name}) updated their account settings.");
InfoMessage->success(t("Your account settings have been saved."));
Router->redirect(Router->generate("account-settings"));
