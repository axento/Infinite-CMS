<?php

class Infinite_Content_UpdateValidator extends Infinite_Content_InsertValidator
{

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


}