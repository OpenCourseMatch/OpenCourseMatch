<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

$statistics = [
    "accountTypes" => [
        "user" => 0,
        "facilitator" => 0,
        "administrator" => 0
    ],
    "userTypes" => [
        "participant" => 0,
        "tutor" => 0
    ],
    "groups" => [
        "default" => 0,
        "customData" => [],
        "customLabels" => []
    ],
    "choices" => [
        "complete" => 0,
        "incomplete" => 0,
        "missing" => 0
    ]
];

$users = User::dao()->getObjects();

foreach($users as $account) {
    if($account->getPermissionLevel() === PermissionLevel::USER->value) {
        $statistics["accountTypes"]["user"]++;

        if($account->getLeadingCourseId() !== null) {
            $statistics["userTypes"]["tutor"]++;
        } else {
            $statistics["userTypes"]["participant"]++;
        }

        if($account->getGroupId() === null) {
            $statistics["groups"]["default"]++;
        } else {
            if(!isset($statistics["groups"]["customData"][$account->getGroupId()])) {
                $statistics["groups"]["customData"][$account->getGroupId()] = 0;
            }
            $statistics["groups"]["customData"][$account->getGroupId()]++;
        }

        $choices = $account->getChoices();
        $allChoices = true;
        $noChoices = true;
        foreach($choices as $choice) {
            if($choice instanceof Choice) {
                $noChoices = false;
            } else {
                $allChoices = false;
            }
        }

        if($allChoices) {
            $statistics["choices"]["complete"]++;
        } else if($noChoices) {
            $statistics["choices"]["missing"]++;
        } else {
            $statistics["choices"]["incomplete"]++;
        }

    } else if($account->getPermissionLevel() === PermissionLevel::FACILITATOR->value) {
        $statistics["accountTypes"]["facilitator"]++;
    } else if($account->getPermissionLevel() === PermissionLevel::ADMIN->value) {
        $statistics["accountTypes"]["administrator"]++;
    }
}

$groups = Group::dao()->getObjects();

foreach($groups as $group) {
    $statistics["groups"]["customLabels"][$group->getId()] = $group->getName();
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
