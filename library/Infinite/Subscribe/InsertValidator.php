<?php

class Infinite_Subscribe_InsertValidator
{

	protected $_subscribe;

	public function validate(Infinite_Subscribe $subscribe)
	{
		$this->_subscribe = $subscribe;

		$this->_validateEmail();

		$msgr = Smart_ErrorStack::getInstance('Infinite_Subscribe');
		if (!$msgr->getNamespaceErrors()) {
			return true;
		}

		return false;
	}

    protected function _validateEmail()
    {
        $msg = Smart_ErrorStack::getInstance('Infinite_Subscribe');
        $validator = new Zend_Validate_EmailAddress();
        if (!$validator->isValid($this->_subscribe->getEmail())) {
            $msg->addMessage('subscribe-email', $validator->getMessages());
        }
        return false == $msg->getErrors('subscribe-email');
    }
 }
