<?php

class Infinite_NewsTag_InsertValidator
{

	protected $_tag;

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

	protected function _validateTag()
	{
		$validator = new Zend_Validate_StringLength(2, 96);
		if ($validator->isValid($this->_tag->getTag())) {
			return true;
		}

		$msg = Infinite_ErrorStack::getInstance('Infinite_NewsTag');
		$msg->addMessage('tag', $validator->getMessages(), 'common');
		return false;
	}
 }
