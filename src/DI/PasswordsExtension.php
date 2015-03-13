<?php

/**
 * This file is part of Ark8 (http://ark8.org)
 *
 * Copyright (c) 2015 Lukáš Unger (looky.msc@gmail.com)
 *
 * For the full copyright and license information, please view the file LICENSE that was distributed with this source code.
 */

namespace Ark8\Passwords\DI;

use Ark8\Passwords\Context;
use Nette\DI\Statement;
use Nette\Utils\Validators;

/**
 * Passwords extension.
 *
 * @author Lukáš Unger <looky.msc@gmail.com>
 */
class PasswordsExtension extends \Nette\DI\CompilerExtension
{

	/** @var array */
	public $defaults = [
		'encryptionKey' => '',
		'signingKey' => '',
		'bcryptCost' => Context::PASSWORD_BCRYPT_DEFAULT_COST,
	];

	/**
	 * @inheritdoc
	 */
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);

		Validators::assertField($config, 'encryptionKey', 'string');
		Validators::assertField($config, 'signingKey', 'string');
		Validators::assertField($config, 'bcryptCost', 'int');
		$builder->addDefinition($this->prefix('passwords'))
			->setClass('Ark8\Passwords\Passwords', [new Statement('Ark8\Passwords\Context', [
				$config['encryptionKey'],
				$config['signingKey'],
				$config['bcryptCost'],
			])]);
	}

}
