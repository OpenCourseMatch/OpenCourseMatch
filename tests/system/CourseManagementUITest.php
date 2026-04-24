<?php

require_once(__DIR__ . "/SystemTestSupport.php");

beforeAll(function() {
    ensureSystemTestData();
});

test("Creating and editing courses works", function() {
    withBrowserPage(function($page) {
        $courseTitle = "System Test Course UI " . uniqid();
        $updatedCourseTitle = $courseTitle . " Updated";

        loginWithCredentials($page, SYSTEM_TEST_FACILITATOR_USERNAME, SYSTEM_TEST_FACILITATOR_PASSWORD);
        $page->goto(Router->generate("courses-create", [], true));
        $page->locator("#title")->fill($courseTitle);
        $page->locator("#organizer")->fill("System Test Organizer");
        $page->locator("#minClearance")->fill("0");
        $page->locator("#maxClearance")->fill("");
        $page->locator("#minParticipants")->fill("0");
        $page->locator("#maxParticipants")->fill("15");
        $page->locator("button[type=\"submit\"]")->click();

        waitForTextOnPage($page, "The course has been saved.");
        $course = \app\courses\Course::dao()->getObject(["title" => $courseTitle]);
        expect($course)->toBeInstanceOf(\app\courses\Course::class);

        $page->goto(Router->generate("courses-edit", ["course" => $course->getId()], true));
        $page->locator("#title")->fill($updatedCourseTitle);
        $page->locator("#organizer")->fill("System Test Organizer Updated");
        $page->locator("#minParticipants")->fill("1");
        $page->locator("#maxParticipants")->fill("20");
        $page->locator("button[type=\"submit\"]")->click();

        waitForTextOnPage($page, "The course has been saved.");
        $updatedCourse = \app\courses\Course::dao()->getObject(["id" => $course->getId()]);
        expect($updatedCourse->getTitle())->toBe($updatedCourseTitle)
            ->and($updatedCourse->getMinParticipants())->toBe(1)
            ->and($updatedCourse->getMaxParticipants())->toBe(20);
    });
});
