<?php

class Infinite_Merchant_InsertValidator
{

    protected $_merchant;

    public function validate(Infinite_Merchant $merchant)
    {
        $this->_merchant = $merchant;

        $this->_validateCompany();
        $this->_validateStreet();
        $this->_validateNumber();
        $this->_validateZip();
        $this->_validateCity();
        $this->_validateContent();

        $msgr = Infinite_ErrorStack::getInstance('Infinite_Merchant');
        if (!$msgr->getNamespaceErrors()) {
            return true;
        }

        return false;
    }

    public function validateShortcut(Infinite_Merchant $merchant)
    {
        $this->_merchant = $merchant;

        $this->_validateCompany();
        $this->_validateWebsite();

        $msgr = Infinite_ErrorStack::getInstance('Infinite_Merchant');
        if (!$msgr->getNamespaceErrors()) {
            return true;
        }

        return false;
    }

    protected function _validateCompany()
    {
        $validator = new Zend_Validate_StringLength(2, 64);
        if ($validator->isValid($this->_merchant->getCompany())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Merchant');
        $msg->addMessage('company', $validator->getMessages(), 'contact');
        return false;
    }

    protected function _validateWebsite()
    {
        $validator = new Zend_Validate_StringLength(2, 64);
        if ($validator->isValid($this->_merchant->getWebsite())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Merchant');
        $msg->addMessage('website', $validator->getMessages(), 'contact');
        return false;
    }

    protected function _validateStreet()
    {
        $validator = new Zend_Validate_StringLength(2, 64);
        if ($validator->isValid($this->_merchant->getStreet())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Merchant');
        $msg->addMessage('street', $validator->getMessages(), 'contact');
        return false;
    }

    protected function _validateNumber()
    {
        $validator = new Zend_Validate_StringLength(1, 8);
        if ($validator->isValid($this->_merchant->getNumber())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Merchant');
        $msg->addMessage('number', $validator->getMessages(), 'contact');
        return false;
    }

    protected function _validateZip()
    {
        $validator = new Zend_Validate_StringLength(4, 8);
        if ($validator->isValid($this->_merchant->getZip())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Merchant');
        $msg->addMessage('zip', $validator->getMessages(), 'contact');
        return false;
    }

    protected function _validateCity()
    {
        $validator = new Zend_Validate_StringLength(2, 64);
        if ($validator->isValid($this->_merchant->getCity())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Merchant');
        $msg->addMessage('city', $validator->getMessages(), 'contact');
        return false;
    }

    protected function _validateContent()
    {
        $validator = new Zend_Validate_StringLength(2);
        if ($validator->isValid($this->_merchant->getContent())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Merchant');
        $msg->addMessage('content', $validator->getMessages(), 'content');
        return false;
    }

}
