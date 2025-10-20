<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));

$get = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "user" => CommonValidators::user(false, [
            "permissionLevel" => \app\users\PermissionLevel::FACILITATOR->value
        ], t("The facilitator that should be edited does not exist."))
    ])
    ->validate($_GET, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("facilitators-overview"));
    });

$account = $get["user"];

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router->generate("dashboard"),
        "iconComponent" => "icons.dashboard"
    ],
    [
        "name" => t("Facilitators"),
        "link" => Router->generate("facilitators-overview")
    ],
    [
        "name" => isset($account) ? t("Edit facilitator \$\$name\$\$", ["name" => $account->getFullName()]) : t("Create facilitator"),
        "link" => Router->generate(isset($account) ? "facilitators-edit" : "facilitators-create", isset($account) ? ["user" => $account->getId()] : [])
    ]
];

echo Blade->run("pages.facilitators.edit", [
    "breadcrumbs" => $breadcrumbs,
    "user" => $account ?? null
]);
