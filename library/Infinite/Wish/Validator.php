<?php

class Infinite_Wish_Validator
{

	protected $_wish;

	public function validate(Infinite_Wish $wish)
	{
		$this->_wish = $wish;

        $this->_validateFromFname();
        $this->_validateFromName();
        $this->_validateToFname();
        $this->_validateToName();
        $this->_validateWish();
        
		$msgr = Infinite_ErrorStack::getInstance('Infinite_Wish');
		if (!$msgr->getNamespaceErrors()) {
			return true;
		}

		return false;
	}

    protected function _validateFromFname()
    {
        $validator = new Zend_Validate_StringLength(2, 96);
        if ($validator->isValid($this->_wish->getFromFname())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Wish');
        $msg->addMessage('fromFname', $validator->getMessages());
        return false;
    }

    protected function _validateFromName()
    {
        $validator = new Zend_Validate_StringLength(2, 96);
        if ($validator->isValid($this->_wish->getFromName())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Wish');
        $msg->addMessage('fromName', $validator->getMessages());
        return false;
    }

    protected function _validateToFname()
    {
        $validator = new Zend_Validate_StringLength(2, 96);
        if ($validator->isValid($this->_wish->getToFname())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Wish');
        $msg->addMessage('toFname', $validator->getMessages());
        return false;
    }

    protected function _validateToName()
    {
        $validator = new Zend_Validate_StringLength(2, 96);
        if ($validator->isValid($this->_wish->getToName())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Wish');
        $msg->addMessage('toName', $validator->getMessages());
        return false;
    }

    protected function _validateWish()
    {
        $validator = new Zend_Validate_StringLength(2, 1024);
        if ($validator->isValid($this->_wish->getWish())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Wish');
        $msg->addMessage('wish', $validator->getMessages());
        return false;
    }
    
}