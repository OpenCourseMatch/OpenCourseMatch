<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

$statistics = [
    "accountTypes" => [
        "user" => 0,
        "facilitator" => 0,
        "administrator" => 0
    ]
];

$users = User::dao()->getObjects();

foreach($users as $account) {
    if($account->getPermissionLevel() === PermissionLevel::USER->value) {
        $statistics["accountTypes"]["user"]++;
    } else if($account->getPermissionLevel() === PermissionLevel::FACILITATOR->value) {
        $statistics["accountTypes"]["facilitator"]++;
    } else if($account->getPermissionLevel() === PermissionLevel::ADMIN->value) {
        $statistics["accountTypes"]["administrator"]++;
    }
}

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router::generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ],
    [
        "name" => t("Statistics"),
        "link" => Router::generate("statistics-overview")
    ]
];

echo Blade->run("statistics.overview", [
    "breadcrumbs" => $breadcrumbs,
    "statistics" => $statistics
]);
