<?php

require __DIR__ . "/.test-setup.php";

test("Add and generate routes", function() {
    Router::addRoute("GET", "/test", "test.php", "getTest");
    Router::addRoute("POST", "/test", "test.php", "postTest");
    Router::addRoute("GET|POST", "/test/{b:bool}", "test.php", "boolTest");
    Router::addRoute("GET|POST", "/test/{f:float}", "test.php", "floatTest");
    Router::addRoute("GET|POST", "/test/{i:int}", "test.php", "intTest");
    Router::addRoute("GET|POST", "/test/{s:string}", "test.php", "stringTest");

    expect(Router::generate("getTest"))->toBe("/test")
        ->and(Router::generate("postTest"))->toBe("/test")
        ->and(Router::generate("boolTest", ["bool" => true]))->toBe("/test/true")
        ->and(Router::generate("boolTest", ["bool" => false]))->toBe("/test/false")
        ->and(Router::generate("floatTest", ["float" => 3.14]))->toBe("/test/3.14")
        ->and(Router::generate("intTest", ["int" => 42]))->toBe("/test/42")
        ->and(Router::generate("stringTest", ["string" => "Hello, World!"]))->toBe("/test/" . urlencode("Hello, World!"));
});
