<?php

use Nette\Security as NS;


/**
 * Users authenticator.
 */
class PasswordAuthenticator extends Nette\Object implements NS\IAuthenticator
{


	/** @var UserModel */
	private $userModel;

	public function __construct(UserModel $userModel)
	{
		$this->userModel = $userModel;
	}


	/**
	 * Performs an authentication
	 * @param  array
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($email, $password) = $credentials;
		$user = $this->userModel->findUser(array('email' => $email));
		\Nette\Diagnostics\Debugger::dump($this->userModel->calculateHash('admin'));

		if (!$user) {
			throw new NS\AuthenticationException("Email '$email' not found.", self::IDENTITY_NOT_FOUND);
		}

		if ($user->password !== $this->userModel->calculateHash($password)) {
			throw new NS\AuthenticationException("Invalid password.", self::INVALID_CREDENTIAL);
		}


		return $this->userModel->createIdentity($user);
	}

}
