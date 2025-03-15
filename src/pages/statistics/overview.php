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
    ],
    "placesByGroup" => [
        "default" => [
            "available" => 0,
            "occupied" => 0,
            "cancelled" => 0
        ],
        "customData" => []
    ],
    "assignments" => [
        "assigned" => 0,
        "notAssigned" => 0,
        "noChoice" => 0
    ],
    "assignmentsByGroup" => [
        "default" => [
            "assigned" => 0,
            "notAssigned" => 0,
            "noChoice" => 0
        ],
        "customData" => []
    ],
    "consideredPriorities" => [
        "none" => 0,
        "courseLeader" => 0,
        "customData" => []
    ]
];

$customGroups = [];

// Fetch group data
$groups = Group::dao()->getObjects();
foreach($groups as $group) {
    $customGroups[$group->getId()] = $group->getName();
}

// Fill priorities with amount of choices
$choiceCount = intval(SystemSetting::dao()->get("choiceCount"));
for($i = 0; $i < $choiceCount; $i++) {
    $statistics["consideredPriorities"]["customData"][$i] = 0;
}

$users = User::dao()->getObjects();
$assignmentsCache = [];

foreach($users as $account) {
    if($account->getPermissionLevel() === PermissionLevel::USER->value) {
        // Account types (user case)
        $statistics["accountTypes"]["user"]++;

        // User types
        if($account->getLeadingCourseId() !== null) {
            $statistics["userTypes"]["tutor"]++;
        } else {
            $statistics["userTypes"]["participant"]++;
        }

        // Groups
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

        // Choices
        if($allChoices) {
            $statistics["choices"]["complete"]++;
        } else if($noChoices) {
            $statistics["choices"]["missing"]++;
        } else {
            $statistics["choices"]["incomplete"]++;
        }

        // Choices by group
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
            // Save assignment for later
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

            // Assignments (assigned case)
            $statistics["assignments"]["assigned"]++;

            // Assignments by group (assigned case)
            if($account->getGroupId() === null) {
                $statistics["assignmentsByGroup"]["default"]["assigned"]++;
            } else {
                if(!isset($statistics["assignmentsByGroup"]["customData"][$account->getGroupId()])) {
                    $statistics["assignmentsByGroup"]["customData"][$account->getGroupId()] = [
                        "assigned" => 0,
                        "notAssigned" => 0,
                        "noChoice" => 0
                    ];
                }

                $statistics["assignmentsByGroup"]["customData"][$account->getGroupId()]["assigned"]++;
            }

            // Course priorities
            $coursePriority = $account->getCoursePriority($assignedCourse);
            if($account->getLeadingCourseId() === $assignedCourse->getId()) {
                $statistics["consideredPriorities"]["courseLeader"]++;
            } else if($coursePriority !== null) {
                $statistics["consideredPriorities"]["customData"][$coursePriority]++;
            } else {
                $statistics["consideredPriorities"]["none"]++;
            }
        } else {
            // Assignments (not assigned case)
            if(!$noChoices) {
                $statistics["assignments"]["notAssigned"]++;
            } else {
                $statistics["assignments"]["noChoice"]++;
            }

            // Assignments by group (not assigned case)
            if($account->getGroupId() === null) {
                if(!$noChoices) {
                    $statistics["assignmentsByGroup"]["default"]["notAssigned"]++;
                } else {
                    $statistics["assignmentsByGroup"]["default"]["noChoice"]++;
                }
            } else {
                if(!isset($statistics["assignmentsByGroup"]["customData"][$account->getGroupId()])) {
                    $statistics["assignmentsByGroup"]["customData"][$account->getGroupId()] = [
                        "assigned" => 0,
                        "notAssigned" => 0,
                        "noChoice" => 0
                    ];
                }

                if(!$noChoices) {
                    $statistics["assignmentsByGroup"]["customData"][$account->getGroupId()]["notAssigned"]++;
                } else {
                    $statistics["assignmentsByGroup"]["customData"][$account->getGroupId()]["noChoice"]++;
                }
            }
        }

    } else if($account->getPermissionLevel() === PermissionLevel::FACILITATOR->value) {
        // Account types (facilitator case)
        $statistics["accountTypes"]["facilitator"]++;
    } else if($account->getPermissionLevel() === PermissionLevel::ADMIN->value) {
        // Account types (administrator case)
        $statistics["accountTypes"]["administrator"]++;
    }
}

$courses = Course::dao()->getObjects();

foreach($courses as $course) {
    $courseLeaders = User::dao()->getObjects([
        "leadingCourseId" => $course->getId()
    ]);

    // Calculate available places
    $totalPlaces = $course->getMaxParticipants();
    $occupiedPlaces = 0;
    if(isset($assignmentsCache[$course->getId()])) {
        $occupiedPlaces = $assignmentsCache[$course->getId()]["excludingCourseLeaders"];
    }
    $availablePlaces = $totalPlaces - $occupiedPlaces;

    if($course->isCancelled()) {
        // Available places (cancelled case)
        $statistics["courseLeaderships"]["cancelled"]++;
        $statistics["places"]["cancelled"] += $availablePlaces;
    } else {
        // Course leaderships
        if(count($courseLeaders) > 0) {
            $statistics["courseLeaderships"]["user"]++;
        } else {
            $statistics["courseLeaderships"]["facilitator"]++;
        }

        // Available places (not cancelled case)
        $statistics["places"]["available"] += $availablePlaces;
        $statistics["places"]["occupied"] += $occupiedPlaces;
    }

    // Available courses by group (default group case)
    if($course->isGroupAllowed(null)) {
        $statistics["coursesByGroup"]["default"]++;

        if($course->isCancelled()) {
            $statistics["placesByGroup"]["default"]["cancelled"] += $availablePlaces;
        } else {
            $statistics["placesByGroup"]["default"]["available"] += $availablePlaces;
            $statistics["placesByGroup"]["default"]["occupied"] += $occupiedPlaces;
        }
    }

    // Available courses by group (custom groups case)
    foreach($groups as $group) {
        if($course->isGroupAllowed($group)) {
            if(!isset($statistics["coursesByGroup"]["customData"][$group->getId()])) {
                $statistics["coursesByGroup"]["customData"][$group->getId()] = 0;
            }

            $statistics["coursesByGroup"]["customData"][$group->getId()]++;

            if(!isset($statistics["placesByGroup"]["customData"][$group->getId()])) {
                $statistics["placesByGroup"]["customData"][$group->getId()] = [
                    "available" => 0,
                    "occupied" => 0,
                    "cancelled" => 0
                ];
            }

            if($course->isCancelled()) {
                $statistics["placesByGroup"]["customData"][$group->getId()]["cancelled"] += $availablePlaces;
            } else {
                $statistics["placesByGroup"]["customData"][$group->getId()]["available"] += $availablePlaces;
                $statistics["placesByGroup"]["customData"][$group->getId()]["occupied"] += $occupiedPlaces;
            }
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
