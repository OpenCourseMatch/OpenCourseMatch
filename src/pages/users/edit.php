<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::FACILITATOR, Router->generate("index"));

$validation = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "user" => CommonValidators::user(false, [
            "permissionLevel" => PermissionLevel::USER->value
        ], t("The user that should be edited does not exist."))
    ])
    ->build();
try {
    $get = $validation->getValidatedValue($_GET);
} catch(\struktal\validation\ValidationException $e) {
    InfoMessage->error($e->getMessage());
    Router->redirect(Router->generate("users-overview"));
}

$account = $get["user"];

$groups = Group::dao()->getObjects();
$courses = Course::dao()->getObjects();

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
