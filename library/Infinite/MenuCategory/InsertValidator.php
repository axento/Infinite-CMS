<?php

class Smart_MenuCategory_BaseValidator
{

    protected $_menuCategory;

    public function validate(Smart_MenuCategory $menuCategory)
    {
        $this->_menuCategory = $menuCategory;

        $this->_validateTitle();

        $msgr = Smart_ErrorStack::getInstance('Smart_MenuCategory');
        if (!$msgr->getNamespaceErrors()) {
            return true;
        }

        return false;
    }

    protected function _validateTitle()
    {
        $validator = new Zend_Validate_StringLength(2, 255);
        if ($validator->isValid($this->_menuCategory->getTitle())) {
            return true;
        }

        $msg = Smart_ErrorStack::getInstance('Smart_MenuCategory');
        $msg->addMessage('title', $validator->getMessages(), 'content');
        return false;
    }

}
