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
        "user" => \validation\Validator::create([
            \validation\IsRequired::create(),
            \validation\IsInDatabase::create(User::dao(), [
                "permissionLevel" => PermissionLevel::USER->value
            ])
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

$account = $post["user"];

// Get warnings for the user
$userWarnings = [];
$assignment = Assignment::dao()->getObject([
    "userId" => $account->getId()
]);
if($assignment instanceof Assignment) {
    $course = $assignment->getCourse();

    // Check if the user has chosen the course
    $chosenCourses = $account->getChoices();
    $chosenCourseIds = array_map(function(?Choice $choice) {
        return $choice?->getCourseId();
    }, $chosenCourses);
    if(!in_array($course->getId(), $chosenCourseIds) && $account->getLeadingCourseId() !== $course->getId()) {
        $userWarnings[] = t("This user has not chosen the course.");
    }

    // Check if the user meets the course requirements
    if(!$course->canChooseCourse($account) && $account->getLeadingCourseId() !== $course->getId()) {
        $userWarnings[] = t("This user does not meet the course requirements.");
    }

    // Check if the user has to be assigned to his own course
    if($account->getLeadingCourse() !== null && !$account->getLeadingCourse()->isCancelled() && $account->getLeadingCourseId() !== $course->getId()) {
        $userWarnings[] = t("This user is not assigned to the course that they are leading.");
    }
} else {
    $userWarnings[] = t("This user is not assigned to any course.");

    // Check if the user has to be assigned to his own course
    if($account->getLeadingCourse() !== null && !$account->getLeadingCourse()->isCancelled()) {
        $userWarnings[] = t("This user is not assigned to the course that they are leading.");
    }
}

// Get all courses
$courses = Course::dao()->getObjects([], "minClearance");

// Course highlighting
$highlighting = [];
$courseWarnings = [];
foreach($courses as $course) {
    $spaceLeft = $course->isSpaceLeft();
    $fulfillsRequirements = $course->canChooseCourse($account);
    $isCancelled = $course->isCancelled();
    $courseLeader = $course->getId() === $account->getLeadingCourseId();

    if(!$spaceLeft && !$courseLeader) {
        $highlighting[$course->getId()] = 2; // Yellow
        $courseWarnings[$course->getId()][] = t("The course is full.");
    }

    if(!$fulfillsRequirements && !$courseLeader) {
        $highlighting[$course->getId()] = 2; // Yellow
        $courseWarnings[$course->getId()][] = t("This user does not meet the course requirements.");
    }

    if($isCancelled) {
        $highlighting[$course->getId()] = 3; // Gray
        $courseWarnings[$course->getId()][] = t("The course has been cancelled.");
    }

    if(!$isCancelled && ($spaceLeft && $fulfillsRequirements || $courseLeader)) {
        $highlighting[$course->getId()] = 1; // Blue
    }
}

// Remove the course to which the user is currently assigned
$assignment = Assignment::dao()->getObject([
    "userId" => $account->getId()
]);
$assignedToLeadingCourse = false;
if($assignment instanceof Assignment) {
    $currentCourse = $assignment->getCourse();
    if($currentCourse->getId() === $account->getLeadingCourseId()) {
        $assignedToLeadingCourse = true;
    }
    $courses = array_filter($courses, function(Course $course) use ($currentCourse) {
        return $course->getId() !== $currentCourse->getId();
    });
}

// Split into chosen, not chosen, and leading course
$leadingCourse = !$assignedToLeadingCourse ? $account->getLeadingCourse() : null;
array_filter($courses, function(Course $course) use ($post, $leadingCourse) {
    return $course->getId() === $post["user"]->getLeadingCourseId();
});

$chosenCourses = [];
foreach($account->getChoices() as $choice) {
    if($choice instanceof Choice) {
        $chosenCourse = $choice->getCourse();
        if($chosenCourse instanceof Course) {
            $chosenCourses[] = $chosenCourse;
        }
    }
}
$chosenCourseIds = array_map(function(Course $course) {
    return $course->getId();
}, $chosenCourses);
array_filter($courses, function(Course $course) use ($chosenCourseIds) {
    return in_array($course->getId(), $chosenCourseIds);
});

// Sort remaining courses by highlighting
usort($courses, function(Course $a, Course $b) use ($highlighting) {
    $highlightA = $highlighting[$a->getId()] ?? 0;
    $highlightB = $highlighting[$b->getId()] ?? 0;
    return $highlightA <=> $highlightB;
});

$html = Blade->run("assignment.components.edit.modal.moveaway", [
    "account" => $account,
    "userWarnings" => $userWarnings,
    "leadingCourse" => $leadingCourse,
    "chosenCourses" => $chosenCourses,
    "otherCourses" => $courses,
    "highlighting" => $highlighting,
    "courseWarnings" => $courseWarnings
]);

Comm::apiSendJson(HTTPResponses::$RESPONSE_OK, [
    "html" => $html
]);
