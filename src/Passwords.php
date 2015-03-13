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
 * Generate encrypted and signed bcrypt hashes.
 *
 * @author Lukáš Unger <looky.msc@gmail.com>
 */
class Passwords extends \Nette\Object
{

	/** @var \Ark8\Passwords\Context */
	private $context;

	/**
	 * @param \Ark8\Passwords\Context $context
	 */
	public function __construct(Context $context)
	{
		$this->context = $context;
	}

	/**
	 * @return \Ark8\Passwords\Context
	 */
	public function getContext()
	{
		return $this->context;
	}

	/**
	 * @param string $hash
	 * @return array
	 */
	public function getInfo($hash)
	{
		$clear = base64_decode($hash);
		$sign = substr($clear, 0, 16) === hash_hmac('md5', substr($clear, 16), $this->context->getSigningKey(), TRUE);
		return password_get_info($sign ? substr(mcrypt_decrypt(
			MCRYPT_RIJNDAEL_128,
			$this->context->getEncryptionKey(),
			substr($clear, 32),
			MCRYPT_MODE_CBC,
			substr($clear, 16, 16)
		), 0, 60) : '');
	}

	/**
	 * @param string $password
	 * @return string
	 */
	public function hash($password)
	{
		$iv = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
		$hash = $iv . mcrypt_encrypt(
			MCRYPT_RIJNDAEL_128,
			$this->context->getEncryptionKey(),
			password_hash($password, PASSWORD_BCRYPT, ['cost' => $this->context->getBcryptCost()]),
			MCRYPT_MODE_CBC,
			$iv
		);
		return base64_encode(hash_hmac('md5', $hash, $this->context->getSigningKey(), TRUE) . $hash);
	}

	/**
	 * @param string $hash
	 * @return bool
	 */
	public function needsRehash($hash)
	{
		$clear = base64_decode($hash);
		$sign = substr($clear, 0, 16) === hash_hmac('md5', substr($clear, 16), $this->context->getSigningKey(), TRUE);
		return password_needs_rehash($sign ? substr(mcrypt_decrypt(
			MCRYPT_RIJNDAEL_128,
			$this->context->getEncryptionKey(),
			substr($clear, 32),
			MCRYPT_MODE_CBC,
			substr($clear, 16, 16)
		), 0, 60) : '', PASSWORD_BCRYPT, ['cost' => $this->context->getBcryptCost()]);
	}

	/**
	 * @param string $password
	 * @param string $hash
	 * @return bool
	 */
	public function verify($password, $hash)
	{
		$clear = base64_decode($hash);
		$sign = substr($clear, 0, 16) === hash_hmac('md5', substr($clear, 16), $this->context->getSigningKey(), TRUE);
		return password_verify($password, substr(mcrypt_decrypt(
			MCRYPT_RIJNDAEL_128,
			$this->context->getEncryptionKey(),
			substr($clear, 32),
			MCRYPT_MODE_CBC,
			substr($clear, 16, 16)
		), 0, 60)) && $sign;
	}

}
