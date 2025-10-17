<?php

use \app\courses\Course;
use \app\courses\CourseService;
use \app\users\User;
use \app\users\PermissionLevel;
use \app\users\UserService;
use \app\choices\Choice;
use \app\choices\ChoiceService;
use \app\assignments\Assignment;
use \app\assignments\AssignmentService;
use \app\groups\Group;
use \app\groups\GroupService;

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));
$coursesAssigned = \app\settings\SystemStatus::dao()->get("coursesAssigned") === "true";

if(!$coursesAssigned) {
    \struktal\API\API::sendWrappedJson([
        "message" => t("An error has occurred whilst attempting to edit the course assignment. Please try again later.")
    ], \struktal\API\HTTPResponse::METHOD_NOT_ALLOWED);
}

$post = Validation->create()
    ->withErrorMessage(t("An error has occurred whilst attempting to edit the course assignment. Please try again later."))
    ->array()
    ->required()
    ->children([
        "user" => CommonValidators::user(true, [
            "permissionLevel" => PermissionLevel::USER->value
        ])
    ])
    ->validate($_POST, function(\struktal\validation\ValidationException $e) {
        \struktal\API\API::sendWrappedJson([
            "message" => $e->getMessage()
        ], \struktal\API\HTTPResponse::BAD_REQUEST);
    });

/** @var User $account */
$account = $post["user"];

// Get warnings for the user
$userWarnings = [];
$assignment = AssignmentService::getAssignmentForUser($account);
if($assignment instanceof Assignment) {
    $course = $assignment->getCourse();

    // Check if the user has chosen the course
    $chosenCourses = ChoiceService::getChoicesOfUser($account);
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
    $leadingCourse = $account->getLeadingCourse();
    if($leadingCourse instanceof Course && !CourseService::isCancelled($leadingCourse) && $leadingCourse->getId()() !== $course->getId()) {
        $userWarnings[] = t("This user is not assigned to the course that they are leading.");
    }
} else {
    $userWarnings[] = t("This user is not assigned to any course.");

    // Check if the user has to be assigned to his own course
    $leadingCourse = $account->getLeadingCourse();
    if($leadingCourse instanceof Course && !CourseService::isCancelled($leadingCourse)) {
        $userWarnings[] = t("This user is not assigned to the course that they are leading.");
    }
}

// Get all courses
$courses = Course::dao()->getObjects([], "minClearance");

// Course highlighting
$highlighting = [];
$courseWarnings = [];
/** @var Course $course */
foreach($courses as $course) {
    $spaceLeft = CourseService::isSpaceLeft($course);
    $fulfillsRequirements = $course->canChooseCourse($account);
    $isCancelled = CourseService::isCancelled($course);
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
foreach(ChoiceService::getChoicesOfUser($account) as $choice) {
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

$html = Blade->run("ui.assignment.modal.moveaway", [
    "account" => $account,
    "userWarnings" => $userWarnings,
    "leadingCourse" => $leadingCourse,
    "chosenCourses" => $chosenCourses,
    "otherCourses" => $courses,
    "highlighting" => $highlighting,
    "courseWarnings" => $courseWarnings
]);

\struktal\API\API::sendWrappedJson([
    "html" => $html
]);
