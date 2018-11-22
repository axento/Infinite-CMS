<?php

class Infinite_News_UpdateValidator extends Infinite_News_InsertValidator
{
	
	public function validate(Infinite_News $news)
	{
		$this->_news = $news;
        $this->_validateTitle();
        $this->_validateContent();
        $this->_validateSummary();
        //$this->_validateImage();
        $this->_validateDatePublication();
        
		$msgr = Infinite_ErrorStack::getInstance('Infinite_News');
		if (!$msgr->getNamespaceErrors()) {
			return true;
		}

		return false;
	}

	
}