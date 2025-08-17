<?php

$user = Auth->enforceLogin(PermissionLevel::ADMIN->value, Router->generate("index"));

$validation = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "user" => CommonValidators::user(true, [
            "permissionLevel" => PermissionLevel::ADMIN->value
        ], t("The administrator that should be edited does not exist."))
    ])
    ->build();
try {
    $get = $validation->getValidatedValue($_GET);
} catch(\struktal\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Router->redirect(Router->generate("administrators-overview"));
}

$account = $get["user"];

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router->generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
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

echo Blade->run("administrators.edit", [
    "breadcrumbs" => $breadcrumbs,
    "user" => $account ?? null
]);
