<?php

class Smart_MenuItem_UpdateValidator extends Smart_MenuItem_BaseValidator
{
	
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

	
}