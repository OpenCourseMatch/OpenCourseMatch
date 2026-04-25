<?php

// Test users
const SYSTEM_TEST_ADMIN_USERNAME = "admin";
const SYSTEM_TEST_ADMIN_PASSWORD = "AdminPassword123!";
const SYSTEM_TEST_FACILITATOR_USERNAME = "facilitator";
const SYSTEM_TEST_FACILITATOR_PASSWORD = "FacilitatorPassword123!";
const SYSTEM_TEST_USER_A_USERNAME = "user-a";
const SYSTEM_TEST_USER_A_PASSWORD = "UserAPassword123!";
const SYSTEM_TEST_USER_B_USERNAME = "user-b";
const SYSTEM_TEST_USER_B_PASSWORD = "UserBPassword123!";

function setupSystemTestData(): void {
    static $initialized = false;
    if ($initialized) {
        return;
    }

    $userData = [
        [ SYSTEM_TEST_ADMIN_USERNAME, SYSTEM_TEST_ADMIN_PASSWORD, \app\users\PermissionLevel::ADMIN, "Admin", "" ],
        [ SYSTEM_TEST_FACILITATOR_USERNAME, SYSTEM_TEST_FACILITATOR_PASSWORD, \app\users\PermissionLevel::FACILITATOR, "Facilitator", "" ],
        [ SYSTEM_TEST_USER_A_USERNAME, SYSTEM_TEST_USER_A_PASSWORD, \app\users\PermissionLevel::USER, "User", "A" ],
        [ SYSTEM_TEST_USER_B_USERNAME, SYSTEM_TEST_USER_B_PASSWORD, \app\users\PermissionLevel::USER, "User", "B" ]
    ];

    foreach ($userData as $data) {
        [$username, $password, $permissionLevel, $firstName, $lastName] = $data;
        if (!\app\users\UserService::userExists($username, $username)) {
            \app\users\UserService::register(
                $username,
                $password,
                $permissionLevel,
                $firstName,
                $lastName,
                null,
                null
            );
        }
    }

    $initialized = true;
}

function handleLoginUI(
    \Playwright\Page\PageInterface $page,
    string $username,
    string $password
): void {
    $page->goto(Router->generate("auth-login", [], true));

    $page->locator("input[name='username']")->fill($username);
    $page->locator("input[name='password']")->fill($password);
    $page->locator("button[type='submit']")->click();
}

function pageHasInfoMessageOfType(
    \Playwright\Page\PageInterface $page,
    \struktal\InfoMessage\InfoMessageType $type
): bool {
    $messageList = $page->locator(".infomessage-list");
    $messages = $messageList->locator("[data-message-type=\"{$type->getName()}\"]");

    return $messages->count() > 0;
}
