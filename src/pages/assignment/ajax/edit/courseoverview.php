<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));
$coursesAssigned = SystemStatus::dao()->get("coursesAssigned") === "true";

if(!$coursesAssigned) {
    \struktal\API\API::sendWrappedJson([
        "message" => t("An error has occurred whilst attempting to edit the course assignment. Please try again later.")
    ], \struktal\API\HTTPResponse::METHOD_NOT_ALLOWED);
}

$validation = Validation->create()
    ->withErrorMessage(t("An error has occurred whilst loading the course overview. Please try again later."))
    ->array()
    ->required()
    ->children([
        "course" => CommonValidators::course(false)
    ])
    ->build();
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\struktal\validation\ValidationException $e) {
    \struktal\API\API::sendWrappedJson([
        "message" => $e->getMessage()
    ], \struktal\API\HTTPResponse::BAD_REQUEST);
}

$courseWarnings = [];
if($post["course"] !== null) {
    // Load the assigned users of the course
    $users = $post["course"]->getAssignedUsers();
    $realParticipantCount = count($post["course"]->getAssignedParticipants());

    // Get warnings for the course
    if($post["course"]->isCancelled()) {
        $courseWarnings[] = t("This course has been cancelled.");
    } else {
        if($post["course"]->getMaxParticipants() < $realParticipantCount) {
            $courseWarnings[] = t("The number of participants exceeds the maximum number of participants allowed for this course.");
        }

        if($post["course"]->getMinParticipants() > $realParticipantCount) {
            $courseWarnings[] = t("The number of participants is below the minimum number of participants required for this course.");
        }

        $courseLeaders = $post["course"]->getAllCourseLeaders();
        $userIds = array_map(function(User $user) {
            return $user->getId();
        }, $users);
        $courseLeaderIds = array_map(function(User $user) {
            return $user->getId();
        }, $courseLeaders);
        if(count(array_diff($courseLeaderIds, $userIds)) > 0) {
            $courseWarnings[] = t("Not all course leaders have been assigned to this course.");
        }
    }
} else {
    // Load unassigned users
    $users = User::dao()->getUnassignedUsers();
    $realParticipantCount = count($users);
}

$html = Blade->run("ui.assignment.courseoverview", [
    "course" => $post["course"],
    "realParticipantCount" => $realParticipantCount,
    "courseWarnings" => $courseWarnings
]);

\struktal\API\API::sendWrappedJson([
    "html" => $html
]);
