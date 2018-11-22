<?php

class Infinite_Header_UpdateValidator extends Infinite_Header_InsertValidator
{
	
	public function validate(Infinite_Header $header)
	{
		$this->_header = $header;
		$this->_validateName();
        //$this->_validateImage();

		$msgr = Infinite_ErrorStack::getInstance('Infinite_Header');
		if (!$msgr->getNamespaceErrors()) {
			return true;
		}

		return false;
	}

	
}