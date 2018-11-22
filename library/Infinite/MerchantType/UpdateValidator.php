<?php

class Infinite_MerchantType_UpdateValidator extends Infinite_MerchantType_InsertValidator
{
	
	public function validate(Infinite_MerchantType $type)
	{
		$this->_merchantType = $type;
        $this->_validateType();
        
		$msgr = Infinite_ErrorStack::getInstance('Infinite_MerchantType');
		if (!$msgr->getNamespaceErrors()) {
			return true;
		}

		return false;
	}
	
}