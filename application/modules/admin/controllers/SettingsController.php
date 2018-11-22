<?php

class Admin_SettingsController extends Zend_Controller_Action
{
    
    public function setLanguageAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		if ($this->getRequest()->isPost()) {
			$_SESSION['System']['lng'] = $this->_getParam('language');

			$goto = '/';
			if ($this->_getParam('goto')) {
				$goto = $this->_getParam('goto');
			}

            $this->_helper->redirector->setPrependBase(false);
            $this->_helper->redirector->gotoUrl($goto);
		}
	}
}
