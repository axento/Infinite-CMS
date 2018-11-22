<?php

class Infinite_Account_UpdateValidator extends Infinite_Account_InsertValidator
{

	protected $_oldPassword = '';

	public function validate(Infinite_Account $account)
	{
		$this->_account = $account;

        $this->_validateEmail();
        if ($account->getPassword()) {
			$this->_validatePassword();
			$this->_validateOldPassword();
        }

		$this->_validateFname();
		$this->_validateName();

		$msgr = Infinite_ErrorStack::getInstance('Infinite_Account');
		if (!$msgr->getNamespaceErrors()) {
			return true;
		}

		return false;
	}

	public function setOldPassword($password)
	{
		$this->_oldPassword = (string) $password;
		return $this;
	}

	protected function _validateOldPassword()
	{
		$db = Zend_Registry::get('db');
		$select = $db->select()
			->from(array('a' => 'Account'), array('*'))
			->where('a.id = ' . (int) $this->_account->getId())
			->where('a.password = ?', sha1($this->_oldPassword));

		if (!$db->fetchRow($select)) {
			$msg = Infinite_ErrorStack::getInstance('Infinite_Account');
			$msg->addMessage('old_password', array('mismatch' => 'Waarde komt niet overeen'), 'password');
			return false;
		}

		return true;
	}
}
