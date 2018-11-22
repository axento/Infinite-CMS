<?php

class Infinite_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $front = Zend_Controller_Front::getInstance();

    	$module   = $request->getModuleName();
    	$crtlact  = $request->getControllerName() . '.' . $request->getActionName();
    	$skiplist = array('account.login');

       	$auth = Zend_Auth::getInstance();
		if ($module === 'admin') {
			$auth->setStorage(new Zend_Auth_Storage_Session('User_Admin'));
		} else {
			$auth->setStorage(new Zend_Auth_Storage_Session('User_Front'));
		}

    	if ($module == 'admin' && !in_array($crtlact, $skiplist)) {
			if (!Zend_Auth::getInstance()->hasIdentity()) {
				$request->setControllerName('account')
					->setActionName('login')
					->setDispatched(false);
			}
    	}
    }
}
