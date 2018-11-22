<?php

class Infinite_Gallery_InsertValidator
{

	protected $_album;

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

    protected function _validateTitle()
	{
		$validator = new Zend_Validate_StringLength(2, 255);
		if ($validator->isValid($this->_album->getTitle())) {
			return true;
		}

		$msg = Infinite_ErrorStack::getInstance('Infinite_Gallery');
		$msg->addMessage('title', $validator->getMessages(), 'content');
		return false;
	}
    
}