<?php

use Playwright\Playwright;

test("Index page is reachable", function() {
    $browser = Playwright::firefox();
    $page = $browser->newPage();

    $request = $page->goto(Router->generate("index", [], true));
    expect($request)->not()->toBeNull()
        ->and($request->ok())->toBeTrue();
});

test("Non-existent routes return 404", function() {
    $browser = Playwright::firefox();
    $page = $browser->newPage();

    $request = $page->goto(Router->generate("index", [], true) . "non-existent-route");
    expect($request)->not()->toBeNull()
        ->and($request->ok())->toBeFalse()
        ->and($request->status())->toBe(404);
});
