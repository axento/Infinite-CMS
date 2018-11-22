<?php

class Infinite_Club_UpdateValidator extends Infinite_Club_InsertValidator
{
	
	public function validate(Infinite_Club $club)
	{
		$this->_club = $club;
        $this->_validateCompany();
        $this->_validateStreet();
        $this->_validateNumber();
        $this->_validateZip();
        $this->_validateCity();
        $this->_validateContent();
        
		$msgr = Infinite_ErrorStack::getInstance('Infinite_Club');
		if (!$msgr->getNamespaceErrors()) {
			return true;
		}

		return false;
	}

    public function validateShortcut(Infinite_Club $club)
    {
        $this->_club = $club;
        $this->_validateCompany();
        $this->_validateWebsite();

        $msgr = Infinite_ErrorStack::getInstance('Infinite_Club');
        if (!$msgr->getNamespaceErrors()) {
            return true;
        }

        return false;
    }

	
}