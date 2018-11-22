<?php

class Infinite_Club_InsertValidator
{

    protected $_club;

    public function validate(Infinite_Club $club)
    {
        $this->_club = $club;

        $this->_validateCompany();
        $this->_validateStreet();
        $this->_validateNumber();
        $this->_validateZip();
        $this->_validateCity();
        $this->_validateContent();

        $msgr = Infinite_ErrorStack::getInstance('Infinite_Club');
        if (!$msgr->getNamespaceErrors()) {
            return true;
        }

        return false;
    }

    public function validateShortcut(Infinite_Club $club)
    {
        $this->_club = $club;

        $this->_validateCompany();
        $this->_validateWebsite();

        $msgr = Infinite_ErrorStack::getInstance('Infinite_Club');
        if (!$msgr->getNamespaceErrors()) {
            return true;
        }

        return false;
    }

    protected function _validateCompany()
    {
        $validator = new Zend_Validate_StringLength(2, 64);
        if ($validator->isValid($this->_club->getCompany())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Club');
        $msg->addMessage('company', $validator->getMessages(), 'contact');
        return false;
    }

    protected function _validateWebsite()
    {
        $validator = new Zend_Validate_StringLength(2, 64);
        if ($validator->isValid($this->_club->getWebsite())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Club');
        $msg->addMessage('website', $validator->getMessages(), 'contact');
        return false;
    }

    protected function _validateStreet()
    {
        $validator = new Zend_Validate_StringLength(2, 64);
        if ($validator->isValid($this->_club->getStreet())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Club');
        $msg->addMessage('street', $validator->getMessages(), 'contact');
        return false;
    }

    protected function _validateNumber()
    {
        $validator = new Zend_Validate_StringLength(2, 8);
        if ($validator->isValid($this->_club->getNumber())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Club');
        $msg->addMessage('number', $validator->getMessages(), 'contact');
        return false;
    }

    protected function _validateZip()
    {
        $validator = new Zend_Validate_StringLength(2, 8);
        if ($validator->isValid($this->_club->getZip())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Club');
        $msg->addMessage('zip', $validator->getMessages(), 'contact');
        return false;
    }

    protected function _validateCity()
    {
        $validator = new Zend_Validate_StringLength(2, 64);
        if ($validator->isValid($this->_club->getCity())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Club');
        $msg->addMessage('city', $validator->getMessages(), 'contact');
        return false;
    }

    protected function _validateContent()
    {
        $validator = new Zend_Validate_StringLength(2);
        if ($validator->isValid($this->_club->getContent())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Club');
        $msg->addMessage('content', $validator->getMessages(), 'content');
        return false;
    }

}
