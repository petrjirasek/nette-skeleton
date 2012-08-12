<?php

class FacebookAuthenticator
{

	/** @var UserModel */
	private $userModel;

	public function __construct(UserModel $userModel)
	{
		$this->userModel = $userModel;
	}

	/**
	 * @param array $fbUser
	 * @return \Nette\Security\Identity
	 */
	public function authenticate(array $fbUser)
	{
		$user = $this->userModel->findUser(array('email' => $fbUser['email']));

		if ($user) {
			$this->updateMissingData($user, $fbUser);
			\Nette\Diagnostics\Debugger::dump('user');
		} else {
			$user = $this->register($fbUser);
		}

		return $this->userModel->createIdentity($user);
	}

	public function register(array $me)
	{
		return $this->userModel->registerUser(array(
			'email' => $me['email'],
			'fbuid' => $me['id'],
			'username' => $me['name'],
		));
	}

	public function updateMissingData($user, array $me)
	{
		$updateData = array();

		if (empty($user['username'])) {
			$updateData['username'] = $me['name'];
		}

		if (empty($user['fbuid'])) {
			$updateData['username'] = $me['id'];
		}

		if (!empty($updateData)) {
			$this->userModel->updateUser($user, $updateData);
		}
	}

}