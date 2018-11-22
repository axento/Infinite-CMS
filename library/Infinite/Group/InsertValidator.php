<?php

class Infinite_Group_InsertValidator
{
	protected $_group;

	public function validate(Infinite_Group $group)
	{
		$this->_group = $group;

		$this->_validateName();

		if (!Infinite_ErrorStack::getInstance('Infinite_Group')->getNamespaceErrors()) {
			return true;
		}

		return false;
	}

	protected function _validateName()
	{
		$validator = new Zend_Validate_StringLength(2, 255);
		if ($validator->isValid($this->_group->getName())) {
			return true;
		}

		$msg = Infinite_ErrorStack::getInstance('Infinite_Group');
		$msg->addMessage('name', $validator->getMessages(),'acl');
		return false;
	}

}
