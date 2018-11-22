<?php

class Infinite_Controller_Plugin_Messenger extends Zend_Controller_Plugin_Abstract
{
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$flash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
		$mvc   = Zend_Layout::getMvcInstance();
		$view  = $mvc->getView();

		$view->flashMessages = $flash->getMessages();
	}
}
