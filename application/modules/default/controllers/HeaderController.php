<?php
class HeaderController extends Zend_Controller_Action
{
	public function init()
    {
        $config = Zend_Registry::get('config');
        $system = new Zend_Session_Namespace('System');
        $system->lng = $this->_getParam('lng', $config->system->defaults->language);
        $this->view->lng = $system->lng;
    }

	public function indexAction()
	{
	    $mapper = new Infinite_Header_DataMapper();
	    $headers = $mapper->getActive();
	    $this->view->headers = $headers;
	    //Zend_Debug::dump($headers);

	}

    public function testAction()
    {
        $mapper = new Infinite_Header_DataMapper();
        $headers = $mapper->getActive();
        $this->view->headers = $headers;
        //Zend_Debug::dump($headers);

    }
}
