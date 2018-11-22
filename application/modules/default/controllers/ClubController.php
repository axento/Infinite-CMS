<?php

class ClubController extends Zend_Controller_Action
{
	public function init()
    {
        $config = Zend_Registry::get('config');
        $system = new Zend_Session_Namespace('System');
        $system->lng = $this->_getParam('lng', $config->system->defaults->language);
        $this->view->lng = $system->lng;

        $this->view->global_tmx = new Zend_Translate(
            array(
                'adapter' => 'tmx',
                'content' => APPLICATION_ROOT . '/application/configs/global.tmx',
                'locale'  => $system->lng
            )
        );
    }

	public function indexAction()
	{

        $mapper = new Infinite_Club_DataMapper();
        $clubs = $mapper->getAllClubs();
        $this->view->clubs = $clubs;

	}

	public function showAction()
	{
	    $lng = $this->_getParam('lng', 'nl');
        $mapper = new Infinite_Content_DataMapper();
		$page = $mapper->getByUrl('verenigingen', $lng);
		$this->view->page = $page;

		$id = $this->_getParam('id');

        $mapper = new Infinite_Club_DataMapper();
        $club = $mapper->getById($id);
        $club->updateRead();

        $this->view->club = $club;

        /** SEO settings */
        if($club->getSeoTitle()) {
            $this->view->headTitle($club->getSeoTitle());
        } else {
            $this->view->headTitle($club->getCompany());
        }
        $page->setSeoDescription($club->getSeoDescription());

        if ($club->getSeoTags()) {
            $page->setSeoTags($club->getSeoTags());
        }
        if ($club->getSeoDescription()) {
            $page->setSeoDescription($club->getSeoDescription());
        }

	}

}
