<?php

class Infinite_Contact_Validator
{

	protected $_contact;

	public function validate(Infinite_Contact $contact)
	{
		$this->_contact = $contact;

        //$this->_validateFname();
        $this->_validateName();
        //$this->_validatePhone();
		$this->_validateEmail();
        $this->_validateMessage();
        
		$msgr = Infinite_ErrorStack::getInstance('Infinite_Contact');
		if (!$msgr->getNamespaceErrors()) {
			return true;
		}

		return false;
	}

    protected function _validateFname()
    {
        $validator = new Zend_Validate_StringLength(2, 96);
        if ($validator->isValid($this->_contact->getFname())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Contact');
        $msg->addMessage('fname', $validator->getMessages());
        return false;
    }

    protected function _validateName()
    {
        $validator = new Zend_Validate_StringLength(2, 96);
        if ($validator->isValid($this->_contact->getName())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Contact');
        $msg->addMessage('name', $validator->getMessages());
        return false;
    }

    protected function _validatePhone()
	{
		$validator = new Zend_Validate_StringLength(2, 24);
		if ($validator->isValid($this->_contact->getPhone())) {
			return true;
		}

		$msg = Infinite_ErrorStack::getInstance('Infinite_Contact');
		$msg->addMessage('phone', $validator->getMessages());
		return false;
	}

    protected function _validateEmail()
    {
        $msg = Infinite_ErrorStack::getInstance('Infinite_Contact');
        $validator = new Zend_Validate_EmailAddress();
        if (!$validator->isValid($this->_contact->getEmail())) {
            $msg->addMessage('email', $validator->getMessages());
        }
        return false == $msg->getErrors('email');
    }

    protected function _validateMessage()
    {
        $validator = new Zend_Validate_StringLength(2, 1024);
        if ($validator->isValid($this->_contact->getMessage())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Contact');
        $msg->addMessage('message', $validator->getMessages());
        return false;
    }
    
}