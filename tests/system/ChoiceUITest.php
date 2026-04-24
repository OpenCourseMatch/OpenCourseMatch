<?php

require_once(__DIR__ . "/SystemTestSupport.php");

beforeAll(function() {
    ensureSystemTestData();
});

test("Choosing courses works", function() {
    withBrowserPage(function($page) {
        $user = getSystemTestUser(SYSTEM_TEST_USER_USERNAME);
        \app\choices\ChoiceService::deleteChoicesForUser($user);

        loginWithCredentials($page, SYSTEM_TEST_USER_USERNAME, SYSTEM_TEST_USER_PASSWORD);
        $page->goto(Router->generate("choice-edit", [], true));

        $page->evaluate("() => {
            const uniqueCourseIds = Array.from(new Set(
                Array.from(document.querySelectorAll('[data-course-id]')).map((element) => element.getAttribute('data-course-id'))
            )).filter((id) => id !== null && id !== '');

            const choiceInputs = Array.from(document.querySelectorAll('input[name=\"choice[]\"]'));
            choiceInputs.forEach((input, index) => {
                input.value = uniqueCourseIds[index] ?? '';
            });

            const submitButton = document.querySelector('button[type=\"submit\"]');
            submitButton?.removeAttribute('disabled');
            document.querySelector('form')?.submit();
        }");

        waitForTextOnPage($page, "Your chosen courses have been saved.");
        waitForCondition(function() use ($user) {
            return count(\app\choices\ChoiceService::getChoicesForUser($user)) === 3;
        });
    });
});
