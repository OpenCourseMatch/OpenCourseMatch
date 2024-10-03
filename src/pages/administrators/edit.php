<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

$validation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "user" => \validation\Validator::create([
            \validation\IsInDatabase::create(User::dao(), [
                "permissionLevel" => PermissionLevel::ADMIN->value,
            ])->setErrorMessage(t("The administrator that should be edited does not exist."))
        ])
    ])
]);
try {
    $get = $validation->getValidatedValue($_GET);
} catch(\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("administrators-overview"));
}

$account = $get["user"];

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router::generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ],
    [
        "name" => t("Administrators"),
        "link" => Router::generate("administrators-overview")
    ],
    [
        "name" => isset($account) ? t("Edit administrator \$\$name\$\$", ["name" => $account->getFullName()]) : t("Create administrator"),
        "link" => Router::generate(isset($account) ? "administrators-edit" : "administrators-create", isset($account) ? ["userId" => $account->getId()] : [])
    ]
];

echo Blade->run("administrators.edit", [
    "breadcrumbs" => $breadcrumbs,
    "user" => $account ?? null
]);
