<?php

class Infinite_Gallery_UpdateValidator extends Infinite_Gallery_InsertValidator
{
	
	public function validate(Infinite_Gallery $album)
	{
		$this->_album = $album;
		$this->_validateTitle();
        
		$msgr = Infinite_ErrorStack::getInstance('Infinite_Gallery');
		if (!$msgr->getNamespaceErrors()) {
			return true;
		}

		return false;
	}

	
}