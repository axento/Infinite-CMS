<?php

class Infinite_Menu_UpdateValidator extends Infinite_Menu_BaseValidator
{
	
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

	
}