<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::FACILITATOR, Router->generate("index"));

$get = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "user" => CommonValidators::user(false, [
            "permissionLevel" => \app\users\PermissionLevel::USER
        ], t("The user that should be edited does not exist."))
    ])
    ->validate($_GET, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("users-overview"));
    });

$account = $get["user"];

$groups = \app\groups\Group::dao()->getObjects();
$courses = \app\courses\Course::dao()->getObjects();

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router->generate("dashboard"),
        "iconComponent" => "icons.dashboard"
    ],
    [
        "name" => t("Participants and tutors"),
        "link" => Router->generate("users-overview")
    ],
    [
        "name" => isset($account) ? t("Edit user \$\$name\$\$", ["name" => $account->getFullName()]) : t("Create user"),
        "link" => Router->generate(isset($account) ? "users-edit" : "users-create", isset($account) ? ["userId" => $account->getId()] : [])
    ]
];

echo Blade->run("pages.users.edit", [
    "breadcrumbs" => $breadcrumbs,
    "user" => $account ?? null,
    "groups" => $groups,
    "courses" => $courses
]);
