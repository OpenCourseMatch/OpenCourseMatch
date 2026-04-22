<?php

use DOMDocument;
use DOMXPath;
use Playwright\Playwright;

const TEST_BASE_URL = "http://localhost:3000";

function createXPath(string $html): DOMXPath {
    $document = new DOMDocument();
    libxml_use_internal_errors(true);
    $document->loadHTML($html);
    libxml_clear_errors();

    return new DOMXPath($document);
}

function visitPage(string $path, callable $assertions): void {
    $browser = Playwright::firefox();
    try {
        $page = $browser->newPage();
        $request = $page->goto(TEST_BASE_URL . $path);
        $assertions($page, $request, createXPath($page->content()));
    } finally {
        $browser->close();
    }
}

test("Landing page loads and links to login", function() {
    visitPage("/", function($page, $request, DOMXPath $xPath) {
        expect($request)->not()->toBeNull()
            ->and($request->ok())->toBeTrue()
            ->and($xPath->evaluate("count(//a[@href='/authentication/login']) > 0"))->toBeTrue();
    });
});

test("Login page displays credential form fields", function() {
    visitPage("/authentication/login", function($page, $request, DOMXPath $xPath) {
        expect($request)->not()->toBeNull()
            ->and($request->ok())->toBeTrue()
            ->and($xPath->evaluate("count(//input[@name='username']) > 0"))->toBeTrue()
            ->and($xPath->evaluate("count(//input[@name='password']) > 0"))->toBeTrue()
            ->and($xPath->evaluate("count(//form[translate(@method, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz') = 'post' and @action='/authentication/login']) > 0"))->toBeTrue();
    });
});

test("Unknown route renders the 404 error page", function() {
    visitPage("/this-route-does-not-exist", function($page, $request, DOMXPath $xPath) {
        expect($request)->not()->toBeNull()
            ->and($request->status())->toBe(404)
            ->and($xPath->evaluate("count(//p[contains(normalize-space(), 'The requested resource could not be found.')]) > 0"))->toBeTrue();
    });
});
