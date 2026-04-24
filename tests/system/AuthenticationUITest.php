<?php

require_once(__DIR__ . "/SystemTestSupport.php");

beforeAll(function() {
    ensureSystemTestData();
});

test("Logging in works", function() {
    withBrowserPage(function($page) {
        loginWithCredentials($page, SYSTEM_TEST_FACILITATOR_USERNAME, SYSTEM_TEST_FACILITATOR_PASSWORD);
        expect($page->content())->toContain("Welcome")
            ->and($page->content())->toContain("System Facilitator");
    });
});

test("Changing password works", function() {
    withBrowserPage(function($page) {
        loginWithCredentials($page, SYSTEM_TEST_PASSWORD_USER_USERNAME, SYSTEM_TEST_PASSWORD_USER_PASSWORD);
        $page->goto(Router->generate("account-settings-change-password", [], true));
        $page->locator("#current-password")->fill(SYSTEM_TEST_PASSWORD_USER_PASSWORD);
        $page->locator("#new-password")->fill("ChangedPassword123!");
        $page->locator("#new-password-repeat")->fill("ChangedPassword123!");
        $page->locator("button[type=\"submit\"]")->click();

        waitForTextOnPage($page, "Your password has been updated.");
        logoutCurrentUser($page);
        loginWithCredentials($page, SYSTEM_TEST_PASSWORD_USER_USERNAME, "ChangedPassword123!");
        expect($page->content())->toContain("System Password");

        upsertSystemTestUser(
            SYSTEM_TEST_PASSWORD_USER_USERNAME,
            SYSTEM_TEST_PASSWORD_USER_PASSWORD,
            \app\users\PermissionLevel::USER,
            "System",
            "Password",
            getSystemTestUser(SYSTEM_TEST_PASSWORD_USER_USERNAME)->getGroupId(),
            null
        );
    });
});
