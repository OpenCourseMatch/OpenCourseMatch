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
    // Load the assigned users of the course
    $users = $post["course"]->getAssignedUsers();
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

// Calculate the real participant count and check for highlighting
$realParticipantCount = 0;
$highlighting = [];
if($post["course"] instanceof Course) {
    foreach($users as $user) {
        if($user->getLeadingCourseId() === null || $user->getLeadingCourseId() !== $post["course"]?->getId()) {
            $realParticipantCount++;

            // Check for highlighting in the user table
            // TODO: Check if user has actually chosen this course!
            $canBeReassigned = false;
            foreach($user->getChoices() as $choice) {
                if($choice instanceof Choice) {
                    $notSameCourse = $choice->getCourseId() !== $post["course"]?->getId();
                    $notCancelled = !$choice->getCourse()?->isCancelled() ?? false;
                    $isSpaceLeft = $choice->getCourse()?->isSpaceLeft() ?? false;

                    if($notSameCourse && $notCancelled && $isSpaceLeft) {
                        $canBeReassigned = true;
                        break;
                    }
                }
            }

            $doesntFulfillRequirements = !$post["course"]?->canChooseCourse($user);
            if($doesntFulfillRequirements) {
                $highlighting[$user->getId()] = 1; // Yellow
            } else if($canBeReassigned) {
                $highlighting[$user->getId()] = 2; // Blue
            }
        }
    }
}

$html = Blade->run("components.courseoverview", [
    "course" => $post["course"],
    "users" => $users,
    "realParticipantCount" => $realParticipantCount,
    "highlighting" => $highlighting
]);

Comm::apiSendJson(HTTPResponses::$RESPONSE_OK, [
    "html" => $html
]);
