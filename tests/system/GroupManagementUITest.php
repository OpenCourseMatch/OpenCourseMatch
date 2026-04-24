<?php

require_once(__DIR__ . "/SystemTestSupport.php");

beforeAll(function() {
    ensureSystemTestData();
});

test("Creating and editing groups works", function() {
    withBrowserPage(function($page) {
        $groupName = "System Test Group UI " . uniqid();
        $updatedGroupName = $groupName . " Updated";

        loginWithCredentials($page, SYSTEM_TEST_ADMIN_USERNAME, SYSTEM_TEST_ADMIN_PASSWORD);
        $page->goto(Router->generate("groups-create", [], true));
        $page->locator("#name")->fill($groupName);
        $page->locator("#clearance")->fill("5");
        $page->locator("button[type=\"submit\"]")->click();

        waitForTextOnPage($page, "The group has been saved.");
        $group = \app\groups\Group::dao()->getObject(["name" => $groupName]);
        expect($group)->toBeInstanceOf(\app\groups\Group::class);

        $page->goto(Router->generate("groups-edit", ["group" => $group->getId()], true));
        $page->locator("#name")->fill($updatedGroupName);
        $page->locator("#clearance")->fill("6");
        $page->locator("button[type=\"submit\"]")->click();

        waitForTextOnPage($page, "The group has been saved.");
        $updatedGroup = \app\groups\Group::dao()->getObject(["id" => $group->getId()]);
        expect($updatedGroup->getName())->toBe($updatedGroupName)
            ->and($updatedGroup->getClearance())->toBe(6);
    });
});
