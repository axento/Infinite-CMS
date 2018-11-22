<?php

class Infinite_Logo_InsertValidator
{

    protected $_logo;

    public function validate(Infinite_Logo $logo)
    {
        $this->_logo = $logo;

        $this->_validateLogo();

        $msgr = Infinite_ErrorStack::getInstance('Infinite_Logo');
        if (!$msgr->getNamespaceErrors()) {
            return true;
        }

        return false;
    }

    protected function _validateLogo()
    {
        $validator = new Zend_Validate_StringLength(2, 64);
        if ($validator->isValid($this->_logo->getLogo())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Logo');
        $msg->addMessage('logo', $validator->getMessages(), 'image');
        return false;
    }

}
