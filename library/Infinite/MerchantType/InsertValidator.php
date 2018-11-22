<?php

class Infinite_MerchantType_InsertValidator
{

    protected $_merchantType;

    public function validate(Infinite_MerchantType $merchantType)
    {
        $this->_merchantType = $merchantType;

        $this->_validateType();

        $msgr = Infinite_ErrorStack::getInstance('Infinite_MerchantType');
        if (!$msgr->getNamespaceErrors()) {
            return true;
        }

        return false;
    }

    protected function _validateType()
    {
        $validator = new Zend_Validate_StringLength(2, 64);
        if ($validator->isValid($this->_merchantType->getType())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_MerchantType');
        $msg->addMessage('type', $validator->getMessages(), 'content');
        return false;
    }

}
