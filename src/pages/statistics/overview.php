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
        "customData" => []
    ],
    "choices" => [
        "complete" => 0,
        "incomplete" => 0,
        "missing" => 0
    ],
    "choicesByGroup" => [
        "default" => [
            "complete" => 0,
            "incomplete" => 0,
            "missing" => 0
        ],
        "customData" => []
    ],
    "courseLeaderships" => [
        "user" => 0,
        "facilitator" => 0
    ]
];

$customGroups = [];

$users = User::dao()->getObjects();

foreach($users as $account) {
    if($account->getPermissionLevel() === PermissionLevel::USER->value) {
        $statistics["accountTypes"]["user"]++;

        if($account->getLeadingCourseId() !== null) {
            $statistics["userTypes"]["tutor"]++;
        } else {
            $statistics["userTypes"]["participant"]++;
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

        if($account->getGroupId() === null) {
            $statistics["groups"]["default"]++;

            if($allChoices) {
                $statistics["choicesByGroup"]["default"]["complete"]++;
            } else if($noChoices) {
                $statistics["choicesByGroup"]["default"]["missing"]++;
            } else {
                $statistics["choicesByGroup"]["default"]["incomplete"]++;
            }
        } else {
            if(!isset($statistics["groups"]["customData"][$account->getGroupId()])) {
                $statistics["groups"]["customData"][$account->getGroupId()] = 0;
            }
            if(!isset($statistics["choicesByGroup"]["customData"][$account->getGroupId()])) {
                $statistics["choicesByGroup"]["customData"][$account->getGroupId()] = [
                    "complete" => 0,
                    "incomplete" => 0,
                    "missing" => 0
                ];
            }

            $statistics["groups"]["customData"][$account->getGroupId()]++;

            if($allChoices) {
                $statistics["choicesByGroup"]["customData"][$account->getGroupId()]["complete"]++;
            } else if($noChoices) {
                $statistics["choicesByGroup"]["customData"][$account->getGroupId()]["missing"]++;
            } else {
                $statistics["choicesByGroup"]["customData"][$account->getGroupId()]["incomplete"]++;
            }
        }

    } else if($account->getPermissionLevel() === PermissionLevel::FACILITATOR->value) {
        $statistics["accountTypes"]["facilitator"]++;
    } else if($account->getPermissionLevel() === PermissionLevel::ADMIN->value) {
        $statistics["accountTypes"]["administrator"]++;
    }
}

$courses = Course::dao()->getObjects();

foreach($courses as $course) {
    $courseLeaders = User::dao()->getObjects([
        "leadingCourseId" => $course->getId()
    ]);

    if(count($courseLeaders) > 0) {
        $statistics["courseLeaderships"]["user"]++;
    } else {
        $statistics["courseLeaderships"]["facilitator"]++;
    }
}

$groups = Group::dao()->getObjects();

foreach($groups as $group) {
    $customGroups[$group->getId()] = $group->getName();
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
    "statistics" => $statistics,
    "customGroups" => $customGroups
]);
