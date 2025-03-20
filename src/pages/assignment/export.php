<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));
$coursesAssigned = SystemStatus::dao()->get("coursesAssigned") === "true";

if(!$coursesAssigned) {
    new InfoMessage(t("An error has occurred whilst attempting to edit the course assignment. Please try again later."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("index"));
}

$assignmentsData = [
    [
        "course" => null,
        "participants" => [],
        "courseLeaders" => []
    ]
];

$accounts = User::dao()->getObjects(["permissionLevel" => PermissionLevel::USER->value]);
$mappedAccounts = [];
foreach($accounts as $account) {
    $mappedAccounts[$account->getId()] = $account;
}

$courseAssignments = Assignment::dao()->getObjects();
$mappedCourseAssignments = [];
foreach($courseAssignments as $courseAssignment) {
    if(!isset($mappedCourseAssignments[$courseAssignment->getCourseId()])) {
        $mappedCourseAssignments[$courseAssignment->getCourseId()] = [];
    }
    $mappedCourseAssignments[$courseAssignment->getCourseId()][] = $courseAssignment;
}

$courses = Course::dao()->getObjects();
foreach($courses as $course) {
    $assignments = $mappedCourseAssignments[$course->getId()] ?? [];
    $participants = [];
    $courseLeaders = [];
    foreach($assignments as $assignment) {
        $account = $mappedAccounts[$assignment->getUserId()] ?? null;
        if($account === null) {
            continue;
        }
        if($account->getLeadingCourseId() === $course->getId()) {
            $courseLeaders[] = $account;
        } else {
            $participants[] = $account;
        }
        unset($mappedAccounts[$account->getId()]);
    }

    usort($participants, function($a, $b) use ($course) {
        // Course leaders are always first
        $aCourseLeader = $a->getLeadingCourseId() !== null && $a->getLeadingCourseId() === $course->getId() ?? -1;
        $bCourseLeader = $b->getLeadingCourseId() !== null && $b->getLeadingCourseId() === $course->getId() ?? -1;
        if($aCourseLeader && !$bCourseLeader) {
            return -1;
        } else if(!$aCourseLeader && $bCourseLeader) {
            return 1;
        }

        // Sort by clearance level
        $aClearance = $a->getGroup()?->getClearance() ?? 0;
        $bClearance = $b->getGroup()?->getClearance() ?? 0;
        if($aClearance !== $bClearance) {
            return $aClearance <=> $bClearance;
        }

        // Sort by full name
        return $a->getFullName() <=> $b->getFullName();
    });

    $assignmentsData[] = [
        "course" => $course,
        "participants" => $participants,
        "courseLeaders" => $courseLeaders
    ];
}

// The remaining accounts are not assigned to any course
$participants = array_values($mappedAccounts);
usort($participants, function($a, $b) {
    // Sort by clearance level
    $aClearance = $a->getGroup()?->getClearance() ?? 0;
    $bClearance = $b->getGroup()?->getClearance() ?? 0;
    if($aClearance !== $bClearance) {
        return $aClearance <=> $bClearance;
    }

    // Sort by full name
    return $a->getFullName() <=> $b->getFullName();
});
$assignmentsData[0]["participants"] = $participants;

header("Content-Type: application/pdf");
$pdf = new PDF($user, t("Course assignment"), "pdf.assignment", [
    "assignments" => $assignmentsData
]);
$pdf->stream();
