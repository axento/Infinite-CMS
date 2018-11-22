<?php

class LogoController extends Zend_Controller_Action
{
	public function init()
    {

    }

    public function indexAction()
    {

        $mapper = new Infinite_Logo_DataMapper();
        $logos = $mapper->getActive();
        shuffle($logos);
        $this->view->logos = $logos;

    }

}
