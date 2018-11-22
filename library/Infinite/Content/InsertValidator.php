<?php

class Infinite_Content_InsertValidator
{

	protected $_content;

	public function validate(Infinite_Content $content)
	{
		$this->_content = $content;

        if ($this->_content->getContentType() == 'page') {
            $this->validateTitle();
            $this->validateContent();
            //$this->validateParent();
        } elseif ($this->_content->getContentType() == 'module') {
            $this->validateTitle();
            //$this->validateParent();
            $this->validateURL();
        } elseif ($this->_content->getContentType() == 'link') {
            $this->validateTitle();
            //$this->validateParent();
            $this->validateLink();
        }

		$msgr = Infinite_ErrorStack::getInstance('Infinite_Content');
		if (!$msgr->getNamespaceErrors()) {
			return true;
		}

		return false;
	}

    public function validateTitle()
    {

        $validator = new Zend_Validate_StringLength(2, 255);
        if ($validator->isValid($this->_content->getTitle())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Content');
        if ($this->_content->getContentType() == 'page') {
                $msg->addMessage('title', $validator->getMessages(), 'content');
        } elseif ($this->_content->getContentType() == 'module') {
                $msg->addMessage('title', $validator->getMessages(), 'external');
        } elseif ($this->_content->getContentType() == 'module') {
            $msg->addMessage('title', $validator->getMessages(), 'link');
        }
        return false;
    }
    /*
    public function validateParent()
    {
        if ($this->_content->getParent() && $this->_content->getParent()->getId() === 0) {
            return true;
        }

        if (!$this->_content->getParent()) {
            $msg = Infinite_ErrorStack::getInstance('Infinite_Content');
            $msg->addMessage('parent', array('required' => 'U moet een bovenliggende pagina selecteren'), 'menu');

            return false;
        } else if ($this->_content->getParent()->getId() === $this->_content->getId()) {
            $msg = Infinite_ErrorStack::getInstance('Infinite_Content');
            $msg->addMessage('parent', array('required' => 'Een pagina kan niet gelinkt zijn aan zichzelf'), 'menu');

            return false;
        }

        return true;
    }
    */
    public function validateURL()
    {

        $validator = new Zend_Validate_StringLength(1);
        if (!$validator->isValid($this->_content->getURL())) {
            $msg = Infinite_ErrorStack::getInstance('Infinite_Content');
            $msg->addMessage('url', $validator->getMessages(), 'external');

            return false;
        }

        return true;
    }

    public function validateLink()
    {

        $validator = new Zend_Validate_StringLength(2,96);
        if ($validator->isValid($this->_content->getLink())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Content');
        $msg->addMessage('link', $validator->getMessages(), 'link');
        return false;
    }

    public function validateContent()
    {

        $validator = new Zend_Validate_StringLength(2);
        if ($validator->isValid($this->_content->getContent())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_Content');
        $msg->addMessage('content', $validator->getMessages(), 'content');
        return false;
    }

}