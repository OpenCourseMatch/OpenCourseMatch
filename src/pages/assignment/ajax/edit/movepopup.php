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

$courses = Course::dao()->getObjects([], "minClearance");

// Course highlighting
$highlighting = [];
$errors = [];
foreach($courses as $course) {
    $spaceLeft = $course->isSpaceLeft();
    $fulfillsRequirements = $course->canChooseCourse($post["user"]);
    $isCancelled = $course->isCancelled();
    $courseLeader = $course->getId() === $post["user"]->getLeadingCourseId();

    if(!$spaceLeft && !$courseLeader) {
        $highlighting[$course->getId()] = 1; // Yellow
        $errors[$course->getId()][] = t("The course is full.");
    }

    if(!$fulfillsRequirements && !$courseLeader) {
        $highlighting[$course->getId()] = 1; // Yellow
        $errors[$course->getId()][] = t("The user does not meet the course requirements.");
    }

    if($isCancelled) {
        $highlighting[$course->getId()] = 3; // Gray
        $errors[$course->getId()][] = t("The course has been cancelled.");
    }

    if(!$isCancelled && ($spaceLeft && $fulfillsRequirements || $courseLeader)) {
        $highlighting[$course->getId()] = 2; // Blue
    }
}

// Remove the course to which the user is currently assigned
$assignment = Allocation::dao()->getObject([
    "userId" => $post["user"]->getId()
]);
$assignedToLeadingCourse = false;
if($assignment instanceof Allocation) {
    $currentCourse = $assignment->getCourse();
    if($currentCourse->getId() === $post["user"]->getLeadingCourseId()) {
        $assignedToLeadingCourse = true;
    }
    $courses = array_filter($courses, function(Course $course) use ($currentCourse) {
        return $course->getId() !== $currentCourse->getId();
    });
}

// Split into chosen, not chosen, and leading course
$leadingCourse = !$assignedToLeadingCourse ? $post["user"]->getLeadingCourse() : null;
array_filter($courses, function(Course $course) use ($post, $leadingCourse) {
    return $course->getId() === $post["user"]->getLeadingCourseId();
});

$chosenCourses = [];
foreach($post["user"]->getChoices() as $choice) {
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

$html = Blade->run("components.movepopup", [
    "leadingCourse" => $leadingCourse,
    "chosenCourses" => $chosenCourses,
    "otherCourses" => $courses,
    "highlighting" => $highlighting,
    "errors" => $errors
]);

Comm::apiSendJson(HTTPResponses::$RESPONSE_OK, [
    "html" => $html
]);
