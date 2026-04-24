<?php

require_once(__DIR__ . "/SystemTestSupport.php");

beforeAll(function() {
    ensureSystemTestData();
});

test("Creating and editing users works", function() {
    withBrowserPage(function($page) {
        $suffix = uniqid();
        $firstName = "UiFirst" . $suffix;
        $lastName = "UiLast" . $suffix;
        $updatedFirstName = "UiFirstUpdated" . $suffix;
        $updatedLastName = "UiLastUpdated" . $suffix;

        loginWithCredentials($page, SYSTEM_TEST_FACILITATOR_USERNAME, SYSTEM_TEST_FACILITATOR_PASSWORD);
        $page->goto(Router->generate("users-create", [], true));
        $page->locator("#firstName")->fill($firstName);
        $page->locator("#lastName")->fill($lastName);
        $page->locator("#password")->fill("UserUiPass123!");
        $page->locator("button[type=\"submit\"]")->click();

        waitForTextOnPage($page, "Participants and tutors");

        $createdUsers = \app\users\User::dao()->getObjects([
            "firstName" => $firstName,
            "lastName" => $lastName
        ]);
        expect($createdUsers)->toHaveCount(1);
        $createdUser = $createdUsers[0];

        $page->goto(Router->generate("users-edit", ["user" => $createdUser->getId()], true));
        $page->locator("#firstName")->fill($updatedFirstName);
        $page->locator("#lastName")->fill($updatedLastName);
        $page->locator("#password")->fill("UserUiPass456!");
        $page->locator("button[type=\"submit\"]")->click();

        waitForTextOnPage($page, "Participants and tutors");
        $updatedUser = \app\users\User::dao()->getObject(["id" => $createdUser->getId()]);
        expect($updatedUser->getFirstName())->toBe($updatedFirstName)
            ->and($updatedUser->getLastName())->toBe($updatedLastName);
    });
});

test("Importing users from CSV file works", function() {
    withBrowserPage(function($page) {
        $beforeFirst = count(\app\users\User::dao()->getObjects(["firstName" => "CsvFirstA", "lastName" => "CsvLastA"]));
        $beforeSecond = count(\app\users\User::dao()->getObjects(["firstName" => "CsvFirstB", "lastName" => "CsvLastB"]));

        loginWithCredentials($page, SYSTEM_TEST_FACILITATOR_USERNAME, SYSTEM_TEST_FACILITATOR_PASSWORD);
        $page->goto(Router->generate("users-import", [], true));
        $page->locator("#file")->setInputFiles(__DIR__ . "/fixtures/users-import.csv");
        $page->locator("button[type=\"submit\"]")->click();

        waitForTextOnPage($page, "Participants and tutors");

        $afterFirst = count(\app\users\User::dao()->getObjects(["firstName" => "CsvFirstA", "lastName" => "CsvLastA"]));
        $afterSecond = count(\app\users\User::dao()->getObjects(["firstName" => "CsvFirstB", "lastName" => "CsvLastB"]));
        expect($afterFirst)->toBeGreaterThan($beforeFirst)
            ->and($afterSecond)->toBeGreaterThan($beforeSecond);
    });
});
