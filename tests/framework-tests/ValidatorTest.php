<?php

require __DIR__ . "/.test-setup.php";

$data = [
    // Check undefined values
    // case0 is undefined
    "case1" => null,

    // Check numeric values
    "case2" => 0,
    "case3" => [1, PHP_INT_MAX],
    "case4" => [-1, PHP_INT_MIN],
    "case5" => 0.0,
    "case6" => [
        3.14, PHP_FLOAT_EPSILON, 0.6
    ],

    // Check strings
    "case7" => "",
    "case8" => "Hello, World!",

    // Check arrays
    "case9" => [],
    "case10" => ["Hello, World!"],
    "case11" => [
        "subcase0" => "Hello, World!",
        "subcase1" => "short",
        "subcase2" => "This, on the other hand, is a very long string."
    ],

    // Check objects
    "case12" => new stdClass(),
    "case13" => new DateFormatter(),

    // Check simulated post validator
    "case14" => [
        "successful" => [
            [
                "field1" => "Hello"
            ],
            [
                "field1" => "Hello",
                "field2" => 1
            ],
            [
                "field1" => "Hello",
                "field2" => null
            ]
        ],
        "failing" => [
            [
                "field1" => ""
            ],
            [
                "field1" => "This is a very long text"
            ],
            [
                "field1" => 3.14
            ],
            [
                "field0" => "Hello"
            ],
            [
                "field1" => "Hello",
                "field2" => "Hello"
            ],
            [
                "field1" => "Hello",
                "field2" => []
            ],
            [
                "field2" => 1
            ]
        ]
    ]
];

$validators = [
    "required1" => \validation\Validator::create([
        \validation\IsRequired::create()
    ]),
    "required2" => \validation\Validator::create([
        \validation\IsRequired::create(true)
    ]),
    "string" => \validation\Validator::create([
        \validation\IsRequired::create(),
        \validation\IsString::create()
    ]),
    "integer" => \validation\Validator::create([
        \validation\IsRequired::create(),
        \validation\IsInteger::create()
    ]),
    "float" => \validation\Validator::create([
        \validation\IsRequired::create(),
        \validation\IsFloat::create()
    ]),
    "array" => \validation\Validator::create([
        \validation\IsRequired::create(),
        \validation\IsArray::create()
    ]),
    "nestedArray" => \validation\Validator::create([
        \validation\IsRequired::create(),
        \validation\IsArray::create(),
        \validation\HasChildren::create([
            \validation\Validator::create([

            ])
        ])
    ]),
    "simulatedPostValidator" => \validation\Validator::create([
        \validation\IsRequired::create(),
        \validation\IsArray::create(),
        \validation\HasChildren::create([
            "field1" => \validation\Validator::create([
                \validation\IsRequired::create(),
                \validation\IsString::create(),
                \validation\MinLength::create(4),
                \validation\MaxLength::create(8),
            ]),
            "field2" => \validation\Validator::create([
                \validation\IsInteger::create()
            ])
        ])
    ])
];

test("Validate undefined value", function() use ($data, $validators) {
    expect(fn() => $validators["required1"]->getValidatedValue($data["case0"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["required2"]->getValidatedValue($data["case0"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["string"]->getValidatedValue($data["case0"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["integer"]->getValidatedValue($data["case0"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["float"]->getValidatedValue($data["case0"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["array"]->getValidatedValue($data["case0"]))->toThrow(\validation\ValidationException::class);
});

test("Validate null", function() use ($data, $validators) {
    expect(fn() => $validators["required1"]->getValidatedValue($data["case1"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["required2"]->getValidatedValue($data["case1"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["string"]->getValidatedValue($data["case1"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["integer"]->getValidatedValue($data["case1"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["float"]->getValidatedValue($data["case1"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["array"]->getValidatedValue($data["case1"]))->toThrow(\validation\ValidationException::class);
});

test("Validate 0 integer", function() use ($data, $validators) {
    expect($validators["required1"]->getValidatedValue($data["case2"]))->toEqual(0)
        ->and(fn() => $validators["required2"]->getValidatedValue($data["case2"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["string"]->getValidatedValue($data["case2"]))->toThrow(\validation\ValidationException::class)
        ->and($validators["integer"]->getValidatedValue($data["case2"]))->toEqual(0)
        ->and(fn() => $validators["float"]->getValidatedValue($data["case2"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["array"]->getValidatedValue($data["case2"]))->toThrow(\validation\ValidationException::class);
});

test("Validate positive integers", function() use ($data, $validators) {
    foreach($data["case3"] as $integer) {
        expect($validators["required1"]->getValidatedValue($integer))->toEqual($integer)
            ->and($validators["required2"]->getValidatedValue($integer))->toEqual($integer)
            ->and(fn() => $validators["string"]->getValidatedValue($integer))->toThrow(\validation\ValidationException::class)
            ->and($validators["integer"]->getValidatedValue($integer))->toEqual($integer)
            ->and(fn() => $validators["float"]->getValidatedValue($integer))->toThrow(\validation\ValidationException::class)
            ->and(fn() => $validators["array"]->getValidatedValue($integer))->toThrow(\validation\ValidationException::class);
    }
});

test("Validate negative integers", function() use ($data, $validators) {
    foreach($data["case4"] as $integer) {
        expect($validators["required1"]->getValidatedValue($integer))->toEqual($integer)
            ->and($validators["required2"]->getValidatedValue($integer))->toEqual($integer)
            ->and(fn() => $validators["string"]->getValidatedValue($integer))->toThrow(\validation\ValidationException::class)
            ->and($validators["integer"]->getValidatedValue($integer))->toEqual($integer)
            ->and(fn() => $validators["float"]->getValidatedValue($integer))->toThrow(\validation\ValidationException::class)
            ->and(fn() => $validators["array"]->getValidatedValue($integer))->toThrow(\validation\ValidationException::class);
    }
});

test("Validate 0 float", function() use ($data, $validators) {
    expect($validators["required1"]->getValidatedValue($data["case5"]))->toEqual(0)
        ->and(fn() => $validators["required2"]->getValidatedValue($data["case5"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["string"]->getValidatedValue($data["case5"]))->toThrow(\validation\ValidationException::class)
        ->and($validators["integer"]->getValidatedValue($data["case5"]))->toEqual(0)
        ->and($validators["float"]->getValidatedValue($data["case5"]))->toEqual(0.0)
        ->and(fn() => $validators["array"]->getValidatedValue($data["case5"]))->toThrow(\validation\ValidationException::class);
});

test("Validate positive floats", function() use ($data, $validators) {
    foreach($data["case6"] as $float) {
        expect($validators["required1"]->getValidatedValue($float))->toEqual($float)
            ->and($validators["required2"]->getValidatedValue($float))->toEqual($float)
            ->and(fn() => $validators["string"]->getValidatedValue($float))->toThrow(\validation\ValidationException::class)
            ->and($validators["integer"]->getValidatedValue($float))->toEqual(floor($float))
            ->and($validators["float"]->getValidatedValue($float))->toEqual($float)
            ->and(fn() => $validators["array"]->getValidatedValue($float))->toThrow(\validation\ValidationException::class);
    }
});

test("Validate empty string", function() use ($data, $validators) {
    expect($validators["required1"]->getValidatedValue($data["case7"]))->toEqual("")
        ->and(fn() => $validators["required2"]->getValidatedValue($data["case7"]))->toThrow(\validation\ValidationException::class)
        ->and($validators["string"]->getValidatedValue($data["case7"]))->toEqual("")
        ->and(fn() => $validators["integer"]->getValidatedValue($data["case7"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["float"]->getValidatedValue($data["case7"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["array"]->getValidatedValue($data["case7"]))->toThrow(\validation\ValidationException::class);
});

test("Validate non-empty string", function() use ($data, $validators) {
    expect($validators["required1"]->getValidatedValue($data["case8"]))->toEqual("Hello, World!")
        ->and($validators["required2"]->getValidatedValue($data["case8"]))->toEqual("Hello, World!")
        ->and($validators["string"]->getValidatedValue($data["case8"]))->toEqual("Hello, World!")
        ->and(fn() => $validators["integer"]->getValidatedValue($data["case8"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["float"]->getValidatedValue($data["case8"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["array"]->getValidatedValue($data["case8"]))->toThrow(\validation\ValidationException::class);
});

test("Validate empty array", function() use ($data, $validators) {
    expect($validators["required1"]->getValidatedValue($data["case9"]))->toEqual([])
        ->and(fn() => $validators["required2"]->getValidatedValue($data["case9"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["string"]->getValidatedValue($data["case9"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["integer"]->getValidatedValue($data["case9"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["float"]->getValidatedValue($data["case9"]))->toThrow(\validation\ValidationException::class)
        ->and($validators["array"]->getValidatedValue($data["case9"]))->toEqual([]);
});

test("Validate non-empty array", function() use ($data, $validators) {
    expect($validators["required1"]->getValidatedValue($data["case10"]))->toEqual(["Hello, World!"])
        ->and($validators["required2"]->getValidatedValue($data["case10"]))->toEqual(["Hello, World!"])
        ->and(fn() => $validators["string"]->getValidatedValue($data["case10"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["integer"]->getValidatedValue($data["case10"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["float"]->getValidatedValue($data["case10"]))->toThrow(\validation\ValidationException::class)
        ->and($validators["array"]->getValidatedValue($data["case10"]))->toEqual(["Hello, World!"]);
});

test("Validate empty object", function() use ($data, $validators) {
    expect($validators["required1"]->getValidatedValue($data["case12"]))->toEqual($data["case12"])
        ->and($validators["required2"]->getValidatedValue($data["case12"]))->toEqual($data["case12"])
        ->and(fn() => $validators["string"]->getValidatedValue($data["case12"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["integer"]->getValidatedValue($data["case12"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["float"]->getValidatedValue($data["case12"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["array"]->getValidatedValue($data["case12"]))->toThrow(\validation\ValidationException::class);
});

test("Validate non-empty object", function() use ($data, $validators) {
    expect($validators["required1"]->getValidatedValue($data["case13"]))->toEqual($data["case13"])
        ->and($validators["required2"]->getValidatedValue($data["case13"]))->toEqual($data["case13"])
        ->and(fn() => $validators["string"]->getValidatedValue($data["case13"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["integer"]->getValidatedValue($data["case13"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["float"]->getValidatedValue($data["case13"]))->toThrow(\validation\ValidationException::class)
        ->and(fn() => $validators["array"]->getValidatedValue($data["case13"]))->toThrow(\validation\ValidationException::class);
});

test("Validate simulated post data", function() use ($data, $validators) {
    $successful = $data["case14"]["successful"];
    $failing = $data["case14"]["failing"];

    foreach($successful as $d) {
        $expected = $d;
        $expected["field1"] = $d["field1"] ?? null;
        $expected["field2"] = $d["field2"] ?? null;
        expect($validators["simulatedPostValidator"]->getValidatedValue($d))->toEqual($expected);
    }

    foreach($failing as $d) {
        expect(fn() => $validators["simulatedPostValidator"]->getValidatedValue($d))->toThrow(\validation\ValidationException::class);
    }
});
