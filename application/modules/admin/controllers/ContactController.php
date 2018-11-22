<?php

class Admin_ContactController extends Zend_Controller_Action
{

	public function init()
	{
        Infinite_Acl::checkAcl('contact');
        $this->view ->placeholder('modules')
                    ->append('active');
        $this->view->contactTab = 'active';
		$config = Zend_Registry::get('config');
		$lngs = $config->system->language;
		$this->view->lngs = $lngs;
	}

    public function indexAction()
	{
		$mapper = new Infinite_Contact_DataMapper();
		$contacts  = $mapper->getAll();
        $this->view->contacts = $contacts;
	} 
    
    public function deleteAction()
	{
                                
        $contact = new Infinite_Contact();
    	$contact->setID($this->_getParam('id'));
		$contact->delete($contact);
        
		$flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$flashMessenger->addMessage('Het item werd succesvol verwijderd!');
        $this->_helper->redirector->gotoSimple('index', 'contact');
	}

}