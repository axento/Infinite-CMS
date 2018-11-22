<?php
class PortfolioController extends Zend_Controller_Action
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
        $this->_helper->layout->setLayout('portfolio');
        $lng = $this->_getParam('lng', 'nl');

        $mapper = new Smart_Content_DataMapper();
        $page = $mapper->getByUrl('projecten', $lng);
        $this->view->page = $page;

        $mapper = new Smart_Portfolio_DataMapper();
        $items = $mapper->getAll();
        $this->view->items = $items;
        //Zend_Debug::dump($items);
	}

    public function showAction()
    {
        $this->_helper->layout->setLayout('portfolio');
        $lng = $this->_getParam('lng', 'nl');

        $mapper = new Smart_Content_DataMapper();
        $page = $mapper->getByUrl('projecten', $lng);
        $this->view->page = $page;

        $mapper = new Smart_Portfolio_DataMapper();
        $project = $mapper->getByID($this->_getParam('id'));
        $this->view->project = $project;
        $this->view->headTitle(ucfirst($project->getTitle()));

        /** het vorige en het volgende project zoeken */
        $mapper = new Smart_Portfolio_DataMapper();
        $next = $mapper->getNext($project);
        $this->view->next = $next;

        $mapper = new Smart_Portfolio_DataMapper();
        $prev = $mapper->getPrev($project);
        $this->view->prev = $prev;
    }
}
