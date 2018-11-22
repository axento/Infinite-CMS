<?php

class Infinite_NewsTag_UpdateValidator extends Infinite_NewsTag_InsertValidator
{
	
	public function validate(Infinite_NewsTag $tag)
	{
		$this->_tag = $tag;
		$this->_validateTag();

		$msgr = Infinite_ErrorStack::getInstance('Infinite_NewsTag');
		if (!$msgr->getNamespaceErrors()) {
			return true;
		}

		return false;
	}

	
}
