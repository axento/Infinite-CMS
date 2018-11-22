<?php

class Smart_MenuCategory_UpdateValidator extends Smart_MenuCategory_BaseValidator
{
	
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

	
}