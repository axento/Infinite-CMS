<?php

class Infinite_Contentblock_Validator
{

	protected $_block;

	public function validate(Infinite_Contentblock $block)
	{
		$this->_block = $block;
        
        $this->validateTitle();
        $this->validateContent();

		$msgr = Infinite_ErrorStack::getInstance('Infinite_Contentblock');
		if (!$msgr->getNamespaceErrors()) {
			return true;
		}

		return false;
	}

    public function validateTitle() {
        $validator = new Zend_Validate_StringLength(2, 255);
        if ($validator->isValid($this->_block->getTitle())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Contentblock');
        $msg->addMessage('title', $validator->getMessages(), 'content');
        return false;
    }

    public function validateContent() {
        $validator = new Zend_Validate_StringLength(2);
        if ($validator->isValid($this->_block->getContent())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Contentblock');
        $msg->addMessage('content', $validator->getMessages(), 'content');
        return false;
    }

}