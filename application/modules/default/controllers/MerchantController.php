<?php

class MerchantController extends Zend_Controller_Action
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

	    $mapper = new Infinite_MerchantType_DataMapper();
	    $types = $mapper->getAll();
	    $this->view->types = $types;

        $mapper = new Infinite_Merchant_DataMapper();
        $merchants = $mapper->getAllMerchants();

        if ($this->getRequest()->isPost() && $this->_getParam('typeID') != 0) {
            $typeID = $this->_getParam('typeID');
            $merchants = $mapper->getByTypeID($typeID);
            $this->view->typeID = $typeID;
        }

        $this->view->merchants = $merchants;
        //Zend_Debug::dump($merchants);

	}

	public function showAction()
	{
	    $lng = $this->_getParam('lng', 'nl');
        $mapper = new Infinite_Content_DataMapper();
		$page = $mapper->getByUrl('handelaars', $lng);
		$this->view->page = $page;

		$id = $this->_getParam('id');

        $mapper = new Infinite_Merchant_DataMapper();
        $merchant = $mapper->getById($id);
        $merchant->updateRead();

        $this->view->merchant = $merchant;

        /** SEO settings */
        if($merchant->getSeoTitle()) {
            $this->view->headTitle($merchant->getSeoTitle());
        } else {
            $this->view->headTitle($merchant->getCompany());
        }
        $page->setSeoDescription($merchant->getSeoDescription());

        if ($merchant->getSeoTags()) {
            $page->setSeoTags($merchant->getSeoTags());
        }
        if ($merchant->getSeoDescription()) {
            $page->setSeoDescription($merchant->getSeoDescription());
        }

	}

}
