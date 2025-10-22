<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));

$get = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "user" => CommonValidators::user(false, [
            "permissionLevel" => \app\users\PermissionLevel::ADMIN
        ], t("The administrator that should be edited does not exist."))
    ])
    ->validate($_GET, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("administrators-overview"));
    });

$account = $get["user"];

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router->generate("dashboard"),
        "iconComponent" => "icons.dashboard"
    ],
    [
        "name" => t("Administrators"),
        "link" => Router->generate("administrators-overview")
    ],
    [
        "name" => isset($account) ? t("Edit administrator \$\$name\$\$", ["name" => $account->getFullName()]) : t("Create administrator"),
        "link" => Router->generate(isset($account) ? "administrators-edit" : "administrators-create", isset($account) ? ["userId" => $account->getId()] : [])
    ]
];

echo Blade->run("pages.administrators.edit", [
    "breadcrumbs" => $breadcrumbs,
    "user" => $account ?? null
]);
