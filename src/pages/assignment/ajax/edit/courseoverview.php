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
            \validation\IsInDatabase::create(Course::dao())
        ])
    ])
])->setErrorMessage(t("An error has occurred whilst loading the course overview. Please try again later."));
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\validation\ValidationException $e) {
    Comm::apiSendJson(HTTPResponses::$RESPONSE_BAD_REQUEST, [
        "message" => $e->getMessage()
    ]);
}

$courseWarnings = [];
if($post["course"] !== null) {
    // Load the assigned users of the course
    $users = $post["course"]->getAssignedUsers();
    $realParticipantCount = count($post["course"]->getAssignedParticipants());

    // Get warnings for the course
    if($post["course"]->isCancelled()) {
        $courseWarnings[] = t("This course has been cancelled.");
    }

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
} else {
    // Load unassigned users
    $users = User::dao()->getUnassignedUsers();
    $realParticipantCount = count($users);
}

$html = Blade->run("assignment.components.edit.courseoverview", [
    "course" => $post["course"],
    "realParticipantCount" => $realParticipantCount,
    "courseWarnings" => $courseWarnings
]);

Comm::apiSendJson(HTTPResponses::$RESPONSE_OK, [
    "html" => $html
]);
