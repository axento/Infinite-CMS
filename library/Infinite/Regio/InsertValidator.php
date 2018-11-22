<?php

class Infinite_Regio_BaseValidator
{

    protected $_regio;

    public function validate(Infinite_Regio $regio)
    {
        $this->_regio = $regio;

        $this->_validateRegio();
        //$this->_validateContent();

        $msgr = Infinite_ErrorStack::getInstance('Infinite_Regio');
        if (!$msgr->getNamespaceErrors()) {
            return true;
        }

        return false;
    }

    protected function _validateRegio()
    {
        $validator = new Zend_Validate_StringLength(2, 64);
        if ($validator->isValid($this->_regio->getRegio())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Regio');
        $msg->addMessage('regio', $validator->getMessages(), 'content');
        return false;
    }

    protected function _validateContent()
    {
        $validator = new Zend_Validate_StringLength(2);
        if ($validator->isValid($this->_regio->getContent())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Regio');
        $msg->addMessage('content', $validator->getMessages(), 'content');
        return false;
    }

}
