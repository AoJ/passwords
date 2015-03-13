<?php

/**
 * Test: Ark8\Passwords\Passwords
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$context = new Ark8\Passwords\Context('abc', 'def', 8);
$passwords = new Ark8\Passwords\Passwords($context);
Assert::same($context, $passwords->getContext());
$hash = $passwords->hash('test');
Assert::same(128, strlen($hash));
Assert::true($passwords->verify('test', $hash));
Assert::false($passwords->verify('fail', $hash));
