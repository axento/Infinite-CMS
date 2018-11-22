<?php

class Infinite_Account_LoginValidator extends Infinite_Account_InsertValidator
{

	public function validate(Infinite_Account $account)
	{
		$this->_account = $account;

		$this->_validateEmail();
		$this->_validatePassword();

		$msgr = Infinite_ErrorStack::getInstance('Infinite_Account');
		if (!$msgr->getNamespaceErrors()) {
			return true;
		}

		return false;
	}

	protected function _validateEmail()
	{
		$validator = new Zend_Validate_EmailAddress();
		$msg = Infinite_ErrorStack::getInstance('Infinite_Account');
		if (!$validator->isValid($this->_account->getEmail())) {
			$msg->addMessage('email', array($this->_account->getEmail()));
		}

		return false == $msg->getErrors('email');
	}

	protected function _validatePassword()
	{
		$validator = new Infinite_Account_PasswordStrength();
		$validator->setRequireDigit(false)
			->setRequireLowercase(false)
			->setRequireUppercase(false);

		$msg = Infinite_ErrorStack::getInstance('Infinite_Account');
		if (!$validator->isValid($this->_account->getPassword())) {
			$msg->addMessage('password', $validator->getMessages());
			return false;
		}

		return false == $msg->getErrors('password');
	}
}
