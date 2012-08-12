<?php

use Nette\Application\UI,
Nette\Security as NS;


/**
 * Sign in/out presenters.
 */
class SignPresenter extends BasePresenter
{


	public function actionIn()
	{
		// facebook
		$fbUrl = $this->context->facebook->getLoginUrl(array(
			'scope' => 'email',
			'redirect_uri' => $this->link('//fbLogin'), // absolute
		));

		$this->template->fbUrl = $fbUrl;
	}

	public function actionFbLogin()
	{
		$me = $this->context->facebook->api('/me');
		$identity = $this->context->facebookAuthenticator->authenticate($me);

		$this->getUser()->login($identity);
		$this->redirect('Homepage:');
	}

	/**
	 * Sign in form component factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm()
	{
		$form = new UI\Form;
		$form->addText('email', 'Email:')
			->setRequired('Please provide a email.')
			->addCondition($form::EMAIL);

		$form->addPassword('password', 'Password:')
			->setRequired('Please provide a password.');

		$form->addCheckbox('remember', 'Remember me on this computer');

		$form->addSubmit('send', 'Sign in');

		$form->onSuccess[] = $this->signInFormSubmitted;
		return $form;
	}


	public function signInFormSubmitted($form)
	{
		try {
			$values = $form->getValues();
			if ($values->remember) {
				$this->getUser()->setExpiration('+ 14 days', FALSE);
			} else {
				$this->getUser()->setExpiration('+ 20 minutes', TRUE);
			}
			$this->getUser()->login($values->email, $values->password);
			$this->redirect('Homepage:');

		} catch (NS\AuthenticationException $e) {
			$form->addError($e->getMessage());
		}
	}


	public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('You have been signed out.');
		$this->redirect('in');
	}

}
