<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));
$coursesAssigned = \app\settings\SystemStatus::dao()->get("coursesAssigned") === "true";

if(!$coursesAssigned) {
    \struktal\API\API::sendWrappedJson([
        "message" => t("An error has occurred whilst loading the course overview. Please try again later.")
    ], \struktal\API\HTTPResponse::METHOD_NOT_ALLOWED);
}

$get = Validation->create()
    ->withErrorMessage(t("An error has occurred whilst loading the course overview. Please try again later."))
    ->array()
    ->required()
    ->children([
        "course" => CommonValidators::course(false)
    ])
    ->validate($_GET, function(\struktal\validation\ValidationException $e) {
        \struktal\API\API::sendWrappedJson([
            "message" => $e->getMessage()
        ], \struktal\API\HTTPResponse::BAD_REQUEST);
    });

if($get["course"] !== null) {
    // Load the assigned users of the course
    $users = $get["course"]->getAssignedUsers();
} else {
    // Load unassigned users
    $users = \app\users\User::dao()->getUnassignedUsers();
}

usort($users, function(\app\users\User $a, \app\users\User $b) use ($get) {
    // Course leaders are always first
    $aCourseLeader = $a->getLeadingCourseId() !== null && $a->getLeadingCourseId() === $get["course"]?->getId() ?? -1;
    $bCourseLeader = $b->getLeadingCourseId() !== null && $b->getLeadingCourseId() === $get["course"]?->getId() ?? -1;
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

function calculateTableHighlighting(bool $isCourseLeader, array $get, \app\users\User $account): int {
    if(!$isCourseLeader) {
        // Checking whether the course requirements are fulfilled is the fastest check, therefore it is done first
        $doesntFulfillRequirements = !$get["course"]?->canChooseCourse($account);
        if($doesntFulfillRequirements) {
            return 2; // Yellow
        }

        // Checking whether the user is leading a course which takes place, but he is not assigned to it
        if($account->getLeadingCourse() !== null && !$account->getLeadingCourse()->isCancelled() && $account->getLeadingCourseId() !== $get["course"]?->getId()) {
            return 2; // Yellow
        }

        // Then we iterate over the chosen courses and check if there is another one with space left AND whether the user has actually chosen the current course
        $canBeReassigned = false;
        $hasChosenCourse = false;
        foreach($account->getChoices() as $choice) {
            if($choice instanceof \app\choices\Choice) {
                $chosenCourse = $choice->getCourse();
                $notSameCourse = $chosenCourse?->getId() !== $get["course"]?->getId();
                $notCancelled = !$chosenCourse?->isCancelled() ?? false;
                $isSpaceLeft = $chosenCourse?->isSpaceLeft() ?? false;

                if(!$notSameCourse) {
                    $hasChosenCourse = true;
                    if($canBeReassigned) {
                        break;
                    }
                }

                if($notSameCourse && $notCancelled && $isSpaceLeft) {
                    $canBeReassigned = true;
                    if($hasChosenCourse) {
                        break;
                    }
                }
            }
        }

        if(!$hasChosenCourse) {
            return 2; // Yellow
        }

        if($canBeReassigned) {
            return 1; // Blue
        }
    }

    return 0;
}

$users = array_map(function(\app\users\User $account) use ($get) {
    $array = $account->toArray();

    // Check if user is course leader
    $isCourseLeader = false;
    if($get["course"] instanceof \app\courses\Course) {
        $isCourseLeader = $account->getLeadingCourseId() === $get["course"]->getId();
    }
    $array["isCourseLeader"] = $isCourseLeader;

    $array["highlighting"] = calculateTableHighlighting($isCourseLeader, $get, $account);

    $group = $account->getGroup();
    if($group instanceof \app\groups\Group) {
        $array["group"] = $group->getName();
    } else {
        $array["group"] = t("Default group");
    }

    unset($array["password"]);
    unset($array["email"]);
    unset($array["emailVerified"]);
    unset($array["permissionLevel"]);
    unset($array["oneTimePassword"]);
    unset($array["oneTimePasswordExpiration"]);
    unset($array["created"]);
    unset($array["updated"]);
    return $array;
}, $users);

\struktal\API\API::sendJson($users);
