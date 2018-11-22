<?php

require_once('Axento/Export.php');
class Admin_SubscribeController extends Zend_Controller_Action
{

	public function init()
	{
        Infinite_Acl::checkAcl('subscribe');
        $this->view ->placeholder('modules')
                    ->append('active');
        $this->view->subscribeTab = 'active';
		$config = Zend_Registry::get('config');
		$lngs = $config->system->language;
		$this->view->lngs = $lngs;
	}

    public function indexAction()
	{
		$mapper = new Infinite_Subscribe_DataMapper();
		$contacts  = $mapper->getAll();
        $this->view->contacts = $contacts;

        $CSVfile = time().".csv";
        exportContacts($contacts,$CSVfile);
        $this->view->file = $CSVfile;
	} 
    
    public function deleteAction()
	{
                                
        $subscribe = new Infinite_Subscribe();
        $subscribe->setID($this->_getParam('id'));
        $subscribe->delete($subscribe);
        
		$flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$flashMessenger->addMessage('Het item werd succesvol verwijderd!');
        $this->_helper->redirector->gotoSimple('index', 'subscribe');
	}

}