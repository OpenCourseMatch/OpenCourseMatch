<?php

$user = Auth::enforceLogin(PermissionLevel::FACILITATOR->value, Router::generate("index"));

$validation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "user" => \validation\Validator::create([
            \validation\IsInDatabase::create(User::dao(), [
                "permissionLevel" => PermissionLevel::USER->value,
            ])->setErrorMessage(t("The user of which the choice should be edited does not exist."))
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

$choosableCourses = Course::dao()->getChoosableCourses($account);
$choiceCount = intval(SystemSetting::dao()->get("choiceCount"));
$saveLink = Router::generate("choice-save-others", ["user" => $account->getId()]);

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
        "name" => isset($account) ? t("Edit user \$\$name\$\$", ["name" => $account->getFullName()]) : t("Create user"),
        "link" => Router::generate(isset($account) ? "users-edit" : "users-create", isset($account) ? ["userId" => $account->getId()] : [])
    ],
    [
        "name" => t("Edit choice"),
        "link" => Router::generate("choice-edit-others", ["user" => $account->getId()])
    ]
];

echo Blade->run("choice.edit", [
    "choosableCourses" => $choosableCourses,
    "choiceCount" => $choiceCount,
    "user" => $account,
    "saveLink" => $saveLink,
    "breadcrumbs" => $breadcrumbs
]);
