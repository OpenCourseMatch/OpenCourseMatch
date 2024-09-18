<?php

$user = Auth::enforceLogin(PermissionLevel::FACILITATOR->value, Router::generate("index"));

$validation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "user" => \validation\Validator::create([
            \validation\IsInDatabase::create(User::dao(), [
                "permissionLevel" => PermissionLevel::USER->value,
            ])->setErrorMessage(t("The user that should be edited does not exist."))
        ])
    ])
]);
try {
    $get = $validation->getValidatedValue($_GET);
} catch(\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("users-overview"));
}

$account = $get["user"];

$groups = Group::dao()->getObjects();
$courses = Course::dao()->getObjects();

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router::generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ],
    [
        "name" => t("Participants and tutors"),
        "link" => Router::generate("users-overview")
    ],
    [
        "name" => t(isset($account) ? "Edit user" : "Create user"),
        "link" => Router::generate(isset($account) ? "users-edit" : "users-create", isset($account) ? ["userId" => $account->getId()] : [])
    ]
];

echo Blade->run("users.edit", [
    "breadcrumbs" => $breadcrumbs,
    "user" => $account ?? null,
    "groups" => $groups,
    "courses" => $courses
]);
