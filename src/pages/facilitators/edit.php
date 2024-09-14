<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

$validation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "user" => \validation\Validator::create([
            \validation\IsInDatabase::create(User::dao(), [
                "permissionLevel" => PermissionLevel::FACILITATOR->value,
            ])->setErrorMessage(t("The facilitator that should be edited does not exist."))
        ])
    ])
]);
try {
    $get = $validation->getValidatedValue($_GET);
} catch(\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("facilitators-overview"));
}

$account = $get["user"];

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router::generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ],
    [
        "name" => t("Facilitators"),
        "link" => Router::generate("facilitators-overview")
    ],
    [
        "name" => t(isset($account) ? "Edit facilitator" : "Create facilitator"),
        "link" => Router::generate(isset($account) ? "facilitators-edit" : "facilitators-create", isset($account) ? ["user" => $account->getId()] : [])
    ]
];

echo Blade->run("facilitators.edit", [
    "breadcrumbs" => $breadcrumbs,
    "user" => $account ?? null
]);
