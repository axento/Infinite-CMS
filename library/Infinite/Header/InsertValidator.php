<?php

class Infinite_Header_InsertValidator
{

    protected $_header;

    public function validate(Infinite_Header $header)
    {
        $this->_header = $header;

        $this->_validateName();
        $this->_validateImage();

        $msgr = Infinite_ErrorStack::getInstance('Infinite_Header');
        if (!$msgr->getNamespaceErrors()) {
            return true;
        }

        return false;
    }

    protected function _validateName()
    {
        $validator = new Zend_Validate_StringLength(2, 64);
        if ($validator->isValid($this->_header->getName())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Header');
        $msg->addMessage('name', $validator->getMessages(), 'content');
        return false;
    }

    protected function _validateImage()
    {
        $validator = new Zend_Validate_StringLength(2, 64);
        if ($validator->isValid($this->_header->getImage())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Header');
        $msg->addMessage('image', $validator->getMessages(), 'image');
        return false;
    }

}
