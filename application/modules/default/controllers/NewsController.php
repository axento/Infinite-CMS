<?php

class NewsController extends Zend_Controller_Action
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

    public function homepageAction()
    {
        $lng = $this->_getParam('lng', 'nl');

        $mapper = new Infinite_News_DataMapper();
        $news = $mapper->getActive($lng,10,0);
        $this->view->news = $news;

    }

	public function indexAction()
	{

        $lng = $this->_getParam('lng', 'nl');

        $mapper = new Infinite_News_DataMapper();
        $news = $mapper->getActive($lng, 9999, 10);
        $this->view->news = $news;



        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($news));
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $paginator->setItemCountPerPage(10);

        $this->view->paginator = $paginator;

	}

	public function showAction()
	{
         $lng = $this->_getParam('lng', 'nl');
         $mapper = new Infinite_Content_DataMapper();
		 $page = $mapper->getByUrl('news', $lng);
		 $this->view->page = $page;

		 $id = $this->_getParam('id');

         $mapper = new Infinite_News_DataMapper();
         $news = $mapper->getById($id);
         $news->updateRead();

        if($news->getGalleries()){
            $pics = array();
            foreach($news->getGalleries() as $gallery){
                $mapper = new Infinite_Gallery_Picture_DataMapper();
                $pics[] = $mapper->getAllByAlbum($gallery, $lng);
            }
            $this->view->pics = $pics;
        }

		$this->_helper->layout->setLayout('default');
        $this->view->news = $news;

        /** SEO settings */
        if($news->getSeoTitle()) {
            $this->view->headTitle($news->getSeoTitle());
        } else {
            $this->view->headTitle($news->getTitle());
        }
        $page->setSeoDescription($news->getSeoDescription());

        if ($news->getSeoTags()) {
            $page->setSeoTags($news->getSeoTags());

        }
        if ($news->getSeoDescription()) {
            $page->setSeoDescription($news->getSeoDescription());
        }

        /** het vorige en het volgende artikel zoeken */
        $mapper = new Infinite_News_DataMapper();
        $next = $mapper->getNext($news,$lng);
        $this->view->next = $next;

        $mapper = new Infinite_News_DataMapper();
        $prev = $mapper->getPrev($news,$lng);
        $this->view->prev = $prev;

	}
}
