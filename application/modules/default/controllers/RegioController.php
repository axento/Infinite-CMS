<?php

class RegioController extends Zend_Controller_Action
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

        $lng = $this->_getParam('lng', 'nl');
        $mapper = new Smart_Content_DataMapper();
        $page = $mapper->getByUrl('blog', $lng);
        $this->view->page = $page;

    }

	public function indexAction()
	{
        $this->_helper->layout->setLayout('seo');
        $lng = $this->_getParam('lng', 'nl');
        $mapper = new Smart_Content_DataMapper();
        $page = $mapper->getByUrl('regios', $lng);
        $this->view->page = $page;

        $mapper = new Smart_Regio_DataMapper();
        $regios = $mapper->getAll();
        $this->view->regios = $regios;

	}

    public function tagsAction()
    {

        $mapper = new Smart_Regio_DataMapper();
        $regios = $mapper->getAll();
        $this->view->regios = $regios;

    }

	public function showAction()
	{
         $lng = $this->_getParam('lng', 'nl');
         $mapper = new Smart_Content_DataMapper();
		 $page = $mapper->getByUrl('regios', $lng);
		 $this->view->page = $page;

		 $url = $this->_getParam('url');

         $mapper = new Smart_Regio_DataMapper();
         $regio = $mapper->getByURL($url);
         $regio->updateRead();

         if (!$regio->getImage()) {
             $regio->setImage('default.jpg');
         }

		 $this->_helper->layout->setLayout('seo');
         $this->view->regio = $regio;

         /** SEO settings */
         if($regio->getSeoTitle()) {
            $this->view->headTitle($regio->getSeoTitle());
         } else {
            $this->view->headTitle($regio->getRegio());
         }
         $page->setSeoDescription($regio->getSeoDescription());

         if ($regio->getSeoTags()) {
             $page->setSeoTags($regio->getSeoTags());
         }
         if ($regio->getSeoDescription()) {
             $page->setSeoDescription($regio->getSeoDescription());
         }

	}

}
