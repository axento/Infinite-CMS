<?php
class EventController extends Zend_Controller_Action
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
        $mapper = new Infinite_Event_DataMapper();
        $events = $mapper->getFutureEvents();
        $this->view->events = $events;
    }

	public function sidebarAction()
	{
	    $mapper = new Infinite_Event_DataMapper();
	    $events = $mapper->getLatest();
	    $this->view->events = $events;

	}
}
