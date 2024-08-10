<?php

require __DIR__ . "/.test-setup.php";

class TestObject extends GenericObject {

}

class TestObjectDAO extends GenericObjectDAO {

}

test("DAO return types", function() {
    $genericObjectDAO1 = GenericObject::dao();
    expect($genericObjectDAO1)->toBeInstanceOf(GenericObjectDAO::class);

    $testObjectDAO1 = TestObject::dao();
    expect($testObjectDAO1)->toBeInstanceOf(TestObjectDAO::class);
});

test("DAO singleton", function() {
    $genericObjectDAO1 = GenericObject::dao();
    $genericObjectDAO2 = GenericObject::dao();
    expect($genericObjectDAO1)->toBeInstanceOf(GenericObjectDAO::class)
        ->and($genericObjectDAO2)->toBeInstanceOf(GenericObjectDAO::class)
        ->and($genericObjectDAO1)->toBe($genericObjectDAO2);
});
