<?php

/**
 * Test: Ark8\Passwords\Context
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$context = new Ark8\Passwords\Context('abc', 'def', 8);

Assert::same(md5('abc', TRUE), $context->getEncryptionKey());
Assert::same(md5('def', TRUE), $context->getSigningKey());
Assert::same(8, $context->getBcryptCost());
