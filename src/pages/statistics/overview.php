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
        "facilitator" => 0,
        "cancelled" => 0
    ],
    "coursesByGroup" => [
        "default" => 0,
        "customData" => []
    ],
    "places" => [
        "available" => 0,
        "occupied" => 0,
        "cancelled" => 0
    ]
];

$customGroups = [];

$groups = Group::dao()->getObjects();

foreach($groups as $group) {
    $customGroups[$group->getId()] = $group->getName();
}

$users = User::dao()->getObjects();
$assignmentsCache = [];

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

        $assignedCourse = $account->getAssignedCourse();
        if($assignedCourse !== null) {
            if(!isset($assignmentsCache[$assignedCourse->getId()])) {
                $assignmentsCache[$assignedCourse->getId()] = [
                    "includingCourseLeaders" => 0,
                    "excludingCourseLeaders" => 0
                ];
            }

            $assignmentsCache[$assignedCourse->getId()]["includingCourseLeaders"]++;
            if($account->getLeadingCourseId() !== $assignedCourse->getId()) {
                $assignmentsCache[$assignedCourse->getId()]["excludingCourseLeaders"]++;
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

    if($course->isCancelled()) {
        $statistics["courseLeaderships"]["cancelled"]++;
        $statistics["places"]["cancelled"] += $course->getMaxParticipants();
    } else {
        if(count($courseLeaders) > 0) {
            $statistics["courseLeaderships"]["user"]++;
        } else {
            $statistics["courseLeaderships"]["facilitator"]++;
        }

        $maxParticipants = $course->getMaxParticipants();
        $assignedParticipants = 0;
        if($assignmentsCache[$course->getId()] !== null) {
            $assignedParticipants = $assignmentsCache[$course->getId()]["excludingCourseLeaders"];
        }
        $statistics["places"]["available"] += $maxParticipants - $assignedParticipants;
        $statistics["places"]["occupied"] += $assignedParticipants;
    }

    if($course->isGroupAllowed(null)) {
        $statistics["coursesByGroup"]["default"]++;
    }

    foreach($groups as $group) {
        if($course->isGroupAllowed($group)) {
            if(!isset($statistics["coursesByGroup"]["customData"][$group->getId()])) {
                $statistics["coursesByGroup"]["customData"][$group->getId()] = 0;
            }

            $statistics["coursesByGroup"]["customData"][$group->getId()]++;
        }
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
    "statistics" => $statistics,
    "customGroups" => $customGroups
]);
