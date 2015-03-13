<?php

/**
 * Test: Ark8\Passwords\DI\PasswordsExtension
 */

use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

$compiler = new Nette\DI\Compiler;
$compiler->addExtension('passwords', new Ark8\Passwords\DI\PasswordsExtension);
$container = createContainer($compiler, "
passwords:
	encryptionKey: abc
	signingKey: def
	bcryptCost: 8
");

$passwords = $container->getByType('Ark8\Passwords\Passwords');
Assert::type('Ark8\Passwords\Passwords', $passwords);
$context = $passwords->getContext();
Assert::same(md5('abc', TRUE), $context->getEncryptionKey());
Assert::same(md5('def', TRUE), $context->getSigningKey());
Assert::same(8, $context->getBcryptCost());
