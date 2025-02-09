<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));
$coursesAssigned = SystemStatus::dao()->get("coursesAssigned") === "true";

if(!$coursesAssigned) {
    Comm::apiSendJson(HTTPResponses::$RESPONSE_METHOD_NOT_ALLOWED, [
        "message" => t("An error has occurred whilst attempting to edit the course assignment. Please try again later.")
    ]);
}

$validation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "course" => \validation\Validator::create([
            \validation\IsRequired::create(),
            \validation\IsInDatabase::create(Course::dao())
        ])
    ])
])->setErrorMessage(t("An error has occurred whilst attempting to edit the course assignment. Please try again later."));
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\validation\ValidationException $e) {
    Comm::apiSendJson(HTTPResponses::$RESPONSE_BAD_REQUEST, [
        "message" => $e->getMessage()
    ]);
}

$course = $post["course"];

// Get the users that chose the course
$choices = Choice::dao()->getObjects([
    "courseId" => $course->getId()
]);
$chosenUserIds = array_map(function(Choice $choice) {
    return $choice->getUserId();
}, $choices);

// Get the users that are assigned to the course
$assignments = Allocation::dao()->getObjects([
    "courseId" => $course->getId()
]);
$assignedUserIds = array_map(function(Allocation $allocation) {
    return $allocation->getUserId();
}, $assignments);

$users = User::dao()->getObjects();
$users = array_filter($users, function(User $account) use ($chosenUserIds, $assignedUserIds, $course) {
    if(in_array($account->getId(), $assignedUserIds)) {
        return false;
    }

    if($account->getLeadingCourseId() === $course->getId()) {
        return true;
    }

    if(in_array($account->getId(), $chosenUserIds)) {
        return true;
    }

    return false;
});

// Sort the users
usort($users, function(User $a, User $b) use ($course) {
    // Course leaders are always first
    $aCourseLeader = $a->getLeadingCourseId() !== null && $a->getLeadingCourseId() === $course->getId();
    $bCourseLeader = $b->getLeadingCourseId() !== null && $b->getLeadingCourseId() === $course->getId();
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

$html = Blade->run("assignment.components.edit.modal.movehere", [
    "users" => $users,
    "course" => $course
]);

Comm::apiSendJson(HTTPResponses::$RESPONSE_OK, [
    "html" => $html
]);
