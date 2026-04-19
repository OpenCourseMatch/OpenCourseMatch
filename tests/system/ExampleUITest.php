<?php

use Playwright\Playwright;

test("Example UI test", function() {
    $browser = Playwright::firefox();
    $page = $browser->newPage();

    $request = $page->goto("http://localhost:3000");
    expect($request)->not()->toBeNull()
        ->and($request->ok())->toBeTrue();

    // Interaction with the page...
});
