<?php

class Infinite_Regio_UpdateValidator extends Infinite_Regio_BaseValidator
{
	
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

	
}