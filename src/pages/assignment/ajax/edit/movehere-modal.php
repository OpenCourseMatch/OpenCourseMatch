<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));
$coursesAssigned = SystemStatus::dao()->get("coursesAssigned") === "true";

if(!$coursesAssigned) {
    \struktal\API\API::sendWrappedJson([
        "message" => t("An error has occurred whilst attempting to edit the course assignment. Please try again later.")
    ], \struktal\API\HTTPResponse::METHOD_NOT_ALLOWED);
}

$validation = Validation->create()
    ->withErrorMessage(t("An error has occurred whilst attempting to edit the course assignment. Please try again later."))
    ->array()
    ->required()
    ->children([
        "course" => CommonValidators::course()
    ])
    ->build();
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\struktal\validation\ValidationException $e) {
    \struktal\API\API::sendWrappedJson([
        "message" => $e->getMessage()
    ], \struktal\API\HTTPResponse::BAD_REQUEST);
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
$assignments = Assignment::dao()->getObjects([
    "courseId" => $course->getId()
]);
$assignedUserIds = array_map(function(Assignment $assignment) {
    return $assignment->getUserId();
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

$html = Blade->run("ui.assignment.modal.movehere", [
    "users" => $users,
    "course" => $course
]);

\struktal\API\API::sendWrappedJson([
    "html" => $html
]);
