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
])->setErrorMessage(t("An error has occurred whilst attempting to edit the course assignment. Please try again later."));
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\validation\ValidationException $e) {
    Comm::apiSendJson(HTTPResponses::$RESPONSE_BAD_REQUEST, [
        "message" => $e->getMessage()
    ]);
}

if($post["course"] !== null) {
    // Load the participants of the course
    $users = $post["course"]->getParticipants();
} else {
    // Load unassigned users
    $users = User::dao()->getUnassignedUsers();
}

usort($users, function(User $a, User $b) use ($post) {
    // Course leaders are always first
    $aCourseLeader = $a->getLeadingCourseId() !== null && $a->getLeadingCourseId() === $post["course"]?->getId() ?? -1;
    $bCourseLeader = $b->getLeadingCourseId() !== null && $b->getLeadingCourseId() === $post["course"]?->getId() ?? -1;
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

$html = Blade->run("components.courseoverview", [
    "course" => $post["course"],
    "users" => $users
]);

Comm::apiSendJson(HTTPResponses::$RESPONSE_OK, [
    "html" => $html
]);
