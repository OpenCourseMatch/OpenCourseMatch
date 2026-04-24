<?php

require_once(__DIR__ . "/SystemTestSupport.php");

beforeAll(function() {
    ensureSystemTestData();
});

test("Assigning courses works", function() {
    withBrowserPage(function($page) {
        \app\settings\SystemStatus::dao()->set("coursesAssigned", "true");
        $assignmentUser = getSystemTestUser(SYSTEM_TEST_ASSIGNMENT_USER_USERNAME);
        $targetCourse = \app\courses\Course::dao()->getObject(["title" => SYSTEM_TEST_COURSE_ONE]);
        expect($targetCourse)->toBeInstanceOf(\app\courses\Course::class);
        \app\assignments\AssignmentService::setAssignedCourseForUser($assignmentUser, null);

        loginWithCredentials($page, SYSTEM_TEST_ADMIN_USERNAME, SYSTEM_TEST_ADMIN_PASSWORD);
        $page->goto(Router->generate("course-assignment-edit", [], true));
        $page->locator("#next-course")->click();
        waitForTextOnPage($page, SYSTEM_TEST_COURSE_ONE);

        $page->locator("#move-here")->click();
        waitForTextOnPage($page, "System");
        waitForTextOnPage($page, "Assignment");

        $page->locator("tr:has-text(\"System Assignment\")")->click();

        waitForCondition(function() use ($assignmentUser, $targetCourse) {
            $assignedCourse = \app\assignments\AssignmentService::getAssignedCourseForUser($assignmentUser);
            return $assignedCourse?->getId() === $targetCourse->getId();
        });
    });
});
