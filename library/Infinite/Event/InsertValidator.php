<?php

class Infinite_Event_InsertValidator
{

    protected $_event;

    public function validate(Infinite_Event $event)
    {
        $this->_event = $event;

        $this->_validateTitle();

        $msgr = Infinite_ErrorStack::getInstance('Infinite_Event');
        if (!$msgr->getNamespaceErrors()) {
            return true;
        }

        return false;
    }

    protected function _validateTitle()
    {
        $validator = new Zend_Validate_StringLength(2, 96);
        if ($validator->isValid($this->_event->getTitle())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Event');
        $msg->addMessage('title', $validator->getMessages(), 'content');
        return false;
    }

}
