<?php

require_once(__DIR__ . "/SystemTestSupport.php");

beforeAll(function() {
    ensureSystemTestData();
});

test("Loading the index page works", function() {
    withBrowserPage(function($page) {
        $response = $page->goto(Router->generate("index", [], true));
        expect($response)->not()->toBeNull()
            ->and($response->ok())->toBeTrue();
        expect($page->content())->toContain("OpenCourseMatch");
    });
});

test("Loading a route that was not registered returns a 404 error", function() {
    withBrowserPage(function($page) {
        $response = $page->goto(Router->generate("index", [], true) . "route-that-does-not-exist");
        expect($response)->not()->toBeNull()
            ->and($response->status())->toBe(404);
        expect($page->content())->toContain("404")
            ->and($page->content())->toContain("The requested resource could not be found.");
    });
});

test("Loading the login page works", function() {
    withBrowserPage(function($page) {
        $response = $page->goto(Router->generate("auth-login", [], true));
        expect($response)->not()->toBeNull()
            ->and($response->ok())->toBeTrue();
        expect($page->content())->toContain("Log in")
            ->and($page->content())->toContain("Username");
    });
});
