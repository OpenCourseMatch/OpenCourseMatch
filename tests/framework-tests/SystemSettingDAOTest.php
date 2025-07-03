<?php

require __DIR__ . "/.test-setup.php";

test("SystemSettingDAO has caching functionality", function() {
    $dao = SystemSetting::dao();
    
    // Test that the DAO has the clearCache method
    expect(method_exists($dao, 'clearCache'))->toBe(true);
    
    // Test clearCache method doesn't throw errors
    $dao->clearCache();
    $dao->clearCache("testKey");
    
    expect(true)->toBe(true); // If we get here, no exceptions were thrown
});

test("SystemSettingDAO caching - clearCache method works", function() {
    $dao = SystemSetting::dao();
    
    // Use reflection to access private cache
    $reflection = new ReflectionClass($dao);
    $cacheProperty = $reflection->getProperty('cache');
    $cacheProperty->setAccessible(true);
    
    // Set some test values in cache directly
    $testCache = [
        "key1" => "value1",
        "key2" => "value2",
        "key3" => "value3"
    ];
    $cacheProperty->setValue($dao, $testCache);
    
    // Verify cache was set
    $currentCache = $cacheProperty->getValue($dao);
    expect($currentCache)->toBe($testCache);
    
    // Test clearing specific key
    $dao->clearCache("key2");
    $cacheAfterSpecific = $cacheProperty->getValue($dao);
    expect($cacheAfterSpecific)->toHaveKey("key1")
        ->and($cacheAfterSpecific)->not->toHaveKey("key2")
        ->and($cacheAfterSpecific)->toHaveKey("key3");
    
    // Test clearing all cache
    $dao->clearCache();
    $cacheAfterAll = $cacheProperty->getValue($dao);
    expect($cacheAfterAll)->toBe([]);
});