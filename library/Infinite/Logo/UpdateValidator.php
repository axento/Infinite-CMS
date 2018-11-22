<?php

class Infinite_Logo_UpdateValidator extends Infinite_Logo_InsertValidator
{
	
	public function validate(Infinite_Logo $logo)
	{
		$this->_logo = $logo;
        //$this->_validateLogo();

		$msgr = Infinite_ErrorStack::getInstance('Infinite_Logo');
		if (!$msgr->getNamespaceErrors()) {
			return true;
		}

		return false;
	}

	
}