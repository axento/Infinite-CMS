<?php

class Infinite_Event_UpdateValidator extends Infinite_Event_InsertValidator
{
	
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

	
}