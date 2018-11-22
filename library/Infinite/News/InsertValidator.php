<?php

class Infinite_News_InsertValidator
{

    protected $_news;

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

    protected function _validateTitle()
    {
        $validator = new Zend_Validate_StringLength(2, 255);
        if ($validator->isValid($this->_news->getTitle())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_News');
        $msg->addMessage('title', $validator->getMessages(), 'content');
        return false;
    }

    protected function _validateContent()
    {
        $validator = new Zend_Validate_StringLength(2);
        if ($validator->isValid($this->_news->getContent())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_News');
        $msg->addMessage('content', $validator->getMessages(), 'content');
        return false;
    }

    protected function _validateSummary()
    {
        $validator = new Zend_Validate_StringLength(2);
        if ($validator->isValid($this->_news->getSummary())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_News');
        $msg->addMessage('summary', $validator->getMessages(), 'content');
        return false;
    }

    protected function _validateImage()
    {
        $validator = new Zend_Validate_StringLength(2, 255);
        if ($validator->isValid($this->_news->getImage())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_News');
        $msg->addMessage('image', $validator->getMessages(), 'image');
        return false;
    }

    protected function _validateDatePublication()
    {
        $validator = new Zend_Validate_StringLength(2, 24);
        if ($validator->isValid($this->_news->getDatePublication())) {
            return true;
        }

        $msg = Infinite_ErrorStack::getInstance('Infinite_News');
        $msg->addMessage('settings', $validator->getMessages(), 'datePublication');
        return false;
    }

}
