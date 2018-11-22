<?php

class Infinite_Account_InsertValidator
{

	protected $_account;
	protected $_passwordRepeat = '';

	public function validate(Infinite_Account $account)
	{
		$this->_account = $account;

		$this->_validateEmail();
        $this->_validatePassword();
		$this->_validateFname();
		$this->_validateName();

		$msgr = Infinite_ErrorStack::getInstance('Infinite_Account');
		if (!$msgr->getNamespaceErrors()) {
			return true;
		}

		return false;
	}
    
	public function updateProfile(Infinite_Account $account)
	{
		$this->_account = $account;

		//$this->_validateEmail();
		$this->_validateFirstName();
		$this->_validateLastName();

		$msgr = Infinite_ErrorStack::getInstance('Infinite_Account');
		if (!$msgr->getNamespaceMessages()) {
			return true;
		}

		return false;
	}

	public function setPasswordRepeat($password)
	{
		$this->_passwordRepeat = (string) $password;
		return $this;
	}

	protected function _validateEmail()
	{
		$validator = new Zend_Validate_EmailAddress();
		$msg = Infinite_ErrorStack::getInstance('Infinite_Account');

		if (!$validator->isValid($this->_account->getEmail())) {
			$msg->addMessage('email', $validator->getMessages());
		}

	    $exclude = array(
            'field' => 'id',
            'value' => (int) $this->_account->getId()
	    );

		$validator = new Zend_Validate_Db_NoRecordExists('Account', 'email', $exclude);
		if (!$validator->isValid($this->_account->getEmail())) {
			$msg->addMessage('email', $validator->getMessages(), 'common');
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
			$msg->addMessage('password', $validator->getMessages(), 'password');
			return false;
		}

		$validator = new Zend_Validate_Identical($this->_account->getPassword());
		if (!$validator->isValid($this->_passwordRepeat)) {
			$msg->addMessage('password_repeat', $validator->getMessages(), 'password');
		}

		return false == ($msg->getErrors('password') &&
			$msg->getErrors('password_repeat'));
	}

	protected function _validateFname()
	{
		$validator = new Zend_Validate_StringLength(2, 255);
		if ($validator->isValid($this->_account->getFname())) {
			return true;
		}

		$msg = Infinite_ErrorStack::getInstance('Infinite_Account');
		$msg->addMessage('fname', $validator->getMessages(), 'common');
		return false;
	}

	protected function _validateName()
	{
		$validator = new Zend_Validate_StringLength(2, 255);
		if ($validator->isValid($this->_account->getName())) {
			return true;
		}

		$msg = Infinite_ErrorStack::getInstance('Infinite_Account');
		$msg->addMessage('name', $validator->getMessages(), 'common');
		return false;
	}
}
