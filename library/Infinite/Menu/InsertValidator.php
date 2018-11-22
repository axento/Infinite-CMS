<?php

class Infinite_Menu_InsertValidator
{

    protected $_menu;

    public function validate(Infinite_Menu $menu)
    {
        $this->_menu = $menu;

        $this->_validateTitle();

        $msgr = Smart_ErrorStack::getInstance('Infinite_Menu');
        if (!$msgr->getNamespaceErrors()) {
            return true;
        }

        return false;
    }

    protected function _validateTitle()
    {
        $validator = new Zend_Validate_StringLength(2, 255);
        if ($validator->isValid($this->_menu->getTitle())) {
            return true;
        }

        $msg = Smart_ErrorStack::getInstance('Infinite_Menu');
        $msg->addMessage('title', $validator->getMessages(), 'content');
        return false;
    }

}
