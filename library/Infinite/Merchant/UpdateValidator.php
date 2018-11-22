<?php

class Infinite_Merchant_UpdateValidator extends Infinite_Merchant_InsertValidator
{
	
	public function validate(Infinite_Merchant $regio)
	{
		$this->_merchant = $regio;
        $this->_validateCompany();
        $this->_validateStreet();
        $this->_validateNumber();
        $this->_validateZip();
        $this->_validateCity();
        $this->_validateContent();
        
		$msgr = Infinite_ErrorStack::getInstance('Infinite_Merchant');
		if (!$msgr->getNamespaceErrors()) {
			return true;
		}

		return false;
	}

    public function validateShortcut(Infinite_Merchant $regio)
    {
        $this->_merchant = $regio;
        $this->_validateCompany();
        $this->_validateWebsite();

        $msgr = Infinite_ErrorStack::getInstance('Infinite_Merchant');
        if (!$msgr->getNamespaceErrors()) {
            return true;
        }

        return false;
    }

	
}