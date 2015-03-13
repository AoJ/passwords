<?php

/**
 * This file is part of Ark8 (http://ark8.org)
 *
 * Copyright (c) 2015 Lukáš Unger (looky.msc@gmail.com)
 *
 * For the full copyright and license information, please view the file LICENSE that was distributed with this source code.
 */

namespace Ark8\Passwords;

/**
 * Passwords context.
 *
 * @author Lukáš Unger <looky.msc@gmail.com>
 */
class Context extends \Nette\Object
{

	/** Default bcrypt cost. */
	const PASSWORD_BCRYPT_DEFAULT_COST = 10;

	/** @var string */
	private $encryptionKey;

	/** @var string */
	private $signingKey;

	/** @var int */
	private $bcryptCost;

	/**
	 * @param string $encryptionKey
	 * @param string $signingKey
	 * @param int $bcryptCost
	 */
	public function __construct($encryptionKey = '', $signingKey = '', $bcryptCost = self::PASSWORD_BCRYPT_DEFAULT_COST)
	{
		$this->encryptionKey = md5($encryptionKey, TRUE);
		$this->signingKey = md5($signingKey, TRUE);
		$this->bcryptCost = (int) $bcryptCost;
	}

	/**
	 * @return string
	 */
	public function getEncryptionKey()
	{
		return $this->encryptionKey;
	}

	/**
	 * @return string
	 */
	public function getSigningKey()
	{
		return $this->signingKey;
	}

	/**
	 * @return int
	 */
	public function getBcryptCost()
	{
		return $this->bcryptCost;
	}

}
