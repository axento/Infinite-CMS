<?php

class Smart_MenuItem_BaseValidator
{

    protected $_menuItem;

    public function validate(Smart_MenuItem $menuItem)
    {
        $this->_menuItem = $menuItem;

        $this->_validateTitle();

        $msgr = Smart_ErrorStack::getInstance('Smart_MenuItem');
        if (!$msgr->getNamespaceErrors()) {
            return true;
        }

        return false;
    }

    protected function _validateTitle()
    {
        $validator = new Zend_Validate_StringLength(2, 255);
        if ($validator->isValid($this->_menuItem->getTitle())) {
            return true;
        }

        $msg = Smart_ErrorStack::getInstance('Smart_MenuItem');
        $msg->addMessage('title', $validator->getMessages(), 'content');
        return false;
    }

}
