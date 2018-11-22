<?php

class Admin_WishController extends Zend_Controller_Action
{

    public function init()
    {
        Infinite_Acl::checkAcl('wish');
        $this->view ->placeholder('modules')
            ->append('active');
        $this->view->wishTab = 'active';
        $config = Zend_Registry::get('config');
        $lngs = $config->system->language;
        $this->view->lngs = $lngs;
    }

    public function indexAction()
    {
        $mapper = new Infinite_Wish_DataMapper();
        $wishes  = $mapper->getAll();
        $this->view->contacts = $wishes;
    }

    public function deleteAction()
    {

        $wish = new Infinite_Wish();
        $wish->setID($this->_getParam('id'));
        $wish->delete($wish);

        $flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $flashMessenger->addMessage('Het item werd succesvol verwijderd!');
        $this->_helper->redirector->gotoSimple('index', 'wish');
    }

    public function activateAction()
    {
        $id = $this->_getParam('id');
        $mapper = new Infinite_Wish_DataMapper();
        $wish = $mapper->getByID($id);
        $wish->activate();

        $flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $flashMessenger->addMessage('De status van het bericht werd succesvol aangepast!');
        $this->_helper->redirector->gotoSimple('index', 'wish');
    }

}