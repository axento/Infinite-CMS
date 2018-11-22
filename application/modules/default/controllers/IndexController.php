<?php

class IndexController extends Zend_Controller_Action
{
    public function init()
    {
        $config = Zend_Registry::get('config');
        $system = new Zend_Session_Namespace('system');
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

	public function contentAction()
	{
        $system = new Zend_Session_Namespace('system');
		$url   = $this->_getParam('url');
        $mapper = new Infinite_Content_DataMapper();
        $page = $mapper->getByURL($url, $this->_getParam('lng', 'nl'));

        if (!$page) {
            $this->_helper->redirector->gotoSimple('error', 'error');
            return;
        }
        if ($page->getId() === false) {
            throw new Zend_Controller_Action_Exception('Page not found', 404);
        }

        /* check for permissions while typing direct URL */
        $identity = Zend_Auth::getInstance()->getIdentity();
        if (!$page->isAllowed($identity)) {
            $this->_helper->redirector->gotoSimple('forbidden', 'error');
            return;
        }

        /* check for galleries */
        if($page->getGalleries()){

            $pics = array();
            foreach($page->getGalleries() as $gallery){

                /* controleren of dit een actief album is */
                $mapper = new Infinite_Gallery_DataMapper();
                $gal = $mapper->getById($gallery);

                if ($gal->getActive() == 1) {
                    $mapper = new Infinite_Gallery_Picture_DataMapper();
                    $pics[] = $mapper->getAllByAlbum($gallery, $system->lng);
                }

            }
            $this->view->pics = $pics;
        }

		$this->view->page = $page;
		$this->_helper->layout->setLayout($page->getLayout());

	}

    public function headAction()
    {

        $expires = date('r',time()+(60*60*24*360)); // For browsercaching (SEO)
        $config = Zend_Registry::get('config');
        $this->view->headTitle()->setSeparator(' | ');

        $this->view->headMeta()->setName('author', $config->meta->author);
        $this->view->headMeta()->setName('keywords',$config->meta->keywords);
        $this->view->headMeta()->setName('description',$config->meta->description);
        $this->view->headMeta()->setName('robots', $config->meta->robots);
        $this->view->headMeta()->setName('expires', $expires);

        if (true == ($page = $this->_getParam('page'))) {

            if($page->getSeoTitle()) {
                $this->view->headTitle($page->getSeoTitle());
            } else {
                if ($page->getParentID() != 0) { // Indien subpagina, ook de titel van de parentpage tonen
                    $mapper = new Infinite_Content_DataMapper();
                    $parent = $mapper->getByID($page->getParentID(), $page->getLanguage());
                    $this->view->headTitle($parent->getTitle() . ' | ' . $page->getTitle());
                } else {
                    $this->view->headTitle($page->getTitle());
                }
            }

            if ($page->getSeoTags()) {
                $this->view->headMeta()->setName('keywords', (string) $page->getSeoTags());
            }
            if ($page->getSeoDescription()) {
                $this->view->headMeta()->setName('description', (string) $page->getSeoDescription());
            }
        }

        $this->view->headTitle($config->meta->headtitle);

    }

    public function navigationAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $system = new Zend_Session_Namespace('System');
        $contentNamespace = new Zend_Session_Namespace('contentNamespace');
        if (isset($contentNamespace->page)) {
            unset($contentNamespace->page);
        }

        $mapper = new Infinite_Content_DataMapper();
        $nav = $mapper->getTree($system->lng);

        $pages = array();

        foreach($nav as $menu) {

            if (!is_array($menu->getChildren()) ) {
                $mapper = new Infinite_Content_DataMapper();
                $page = $mapper->getByID($menu->getID(),$system->lng);
                if ($page == $this->_getParam('page')) {
                    $page->setActive(true);
                }
            } else {
                $mapper = new Infinite_Content_DataMapper();
                $page = $mapper->getByID($menu->getID(),$system->lng);
                if ($page == $this->_getParam('page')) {
                    $page->setActive(true);
                }

                foreach($menu->getChildren() as $submenu) {
                    $mapper = new Infinite_Content_DataMapper();
                    $child = $mapper->getByID($submenu->getID(),$system->lng);
                    if ($child == $this->_getParam('page')) {
                        $page->setActive(true);
                    }
                    $page->addChild($child);
                }
            }

            // Nagaan welke groepen rechten hebben om deze pagina te bekijken
            $mapper = new Infinite_ContentAcl_DataMapper();
            $acls = $mapper->getByContentID($menu->getID());
            $page->setPermissions($acls);

            if($page->isAllowed($identity)) {
                $pages[] = $page;
            }

        }
       // Zend_Debug::dump($pages);
        $this->view->pages = $pages;

    }

    public function navtabletAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $system = new Zend_Session_Namespace('System');
        $contentNamespace = new Zend_Session_Namespace('contentNamespace');
        if (isset($contentNamespace->page)) {
            unset($contentNamespace->page);
        }

        $mapper = new Infinite_Content_DataMapper();
        $nav = $mapper->getTree($system->lng);

        $pages = array();

        foreach($nav as $menu) {

            if (!is_array($menu->getChildren()) ) {
                $mapper = new Infinite_Content_DataMapper();
                $page = $mapper->getByID($menu->getID(),$system->lng);
                if ($page == $this->_getParam('page')) {
                    $page->setActive(true);
                }
            } else {
                $mapper = new Infinite_Content_DataMapper();
                $page = $mapper->getByID($menu->getID(),$system->lng);
                if ($page == $this->_getParam('page')) {
                    $page->setActive(true);
                }

                foreach($menu->getChildren() as $submenu) {
                    $mapper = new Infinite_Content_DataMapper();
                    $child = $mapper->getByID($submenu->getID(),$system->lng);
                    if ($child == $this->_getParam('page')) {
                        $page->setActive(true);
                    }
                    $page->addChild($child);
                }
            }

            // Nagaan welke groepen rechten hebben om deze pagina te bekijken
            $mapper = new Infinite_ContentAcl_DataMapper();
            $acls = $mapper->getByContentID($menu->getID());
            $page->setPermissions($acls);

            if($page->isAllowed($identity)) {
                $pages[] = $page;
            }

        }
        // Zend_Debug::dump($pages);
        $this->view->pages = $pages;

    }

    public function navphoneAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $system = new Zend_Session_Namespace('System');
        $contentNamespace = new Zend_Session_Namespace('contentNamespace');
        if (isset($contentNamespace->page)) {
            unset($contentNamespace->page);
        }

        $mapper = new Infinite_Content_DataMapper();
        $nav = $mapper->getTree($system->lng);

        $pages = array();

        foreach($nav as $menu) {

            if (!is_array($menu->getChildren()) ) {
                $mapper = new Infinite_Content_DataMapper();
                $page = $mapper->getByID($menu->getID(),$system->lng);
                if ($page == $this->_getParam('page')) {
                    $page->setActive(true);
                }
            } else {
                $mapper = new Infinite_Content_DataMapper();
                $page = $mapper->getByID($menu->getID(),$system->lng);
                if ($page == $this->_getParam('page')) {
                    $page->setActive(true);
                }

                foreach($menu->getChildren() as $submenu) {
                    $mapper = new Infinite_Content_DataMapper();
                    $child = $mapper->getByID($submenu->getID(),$system->lng);
                    if ($child == $this->_getParam('page')) {
                        $page->setActive(true);
                    }
                    $page->addChild($child);
                }
            }

            // Nagaan welke groepen rechten hebben om deze pagina te bekijken
            $mapper = new Infinite_ContentAcl_DataMapper();
            $acls = $mapper->getByContentID($menu->getID());
            $page->setPermissions($acls);

            if($page->isAllowed($identity)) {
                $pages[] = $page;
            }

        }
        // Zend_Debug::dump($pages);
        $this->view->pages = $pages;

    }


    public function breadcrumbsAction()
    {
        $system = new Zend_Session_Namespace('System');
        $page = $this->_getParam('page');
        $mapper = new Infinite_Content_DataMapper();
        $current = $mapper->getByID($page->getID(), $system->lng);

        $breadcrumbs = array();
        $breadcrumbs[$current->getID()] = array(
            'label' => $current->getTitle(),
            'url' => $current->getURL(),
            'active' => true
        );
        $parentID = $current->getParentID();

        while ($parentID != 0) {
            $mapper = new Infinite_Content_DataMapper();
            $parent = $mapper->getByID($parentID, $system->lng);
            $breadcrumbs[$parentID] = array(
                'label' => $parent->getTitle(),
                'url' => $parent->getURL(),
                'active' => false
            );
            $parentID = $parent->getParentID();
        }

        $mapper = new Infinite_Content_DataMapper();
        $homepage = $mapper->getByID(0, $system->lng);
        $breadcrumbs[0] = array(
            'label' => $homepage->getTitle(),
            'url' => $homepage->getURL(),
            'active' => false
        );

        $breadcrumbs = array_reverse($breadcrumbs);//die;
        $this->view->breadcrumbs = $breadcrumbs;

    }

    public function fastlinkAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $id = $this->_getParam('id');
        $lng = $this->_getParam('lng');

        $mapper = new Infinite_Content_DataMapper();
        $page = $mapper->getById($id,$lng);

        switch ($page && $page->getContentType()) {
            case 'module':
                $this->_helper->redirector->gotoUrl('/' . $lng . '/' . $page->getURL());
                break;
            case 'page':
                $this->_helper->redirector->gotoRoute(array('url' => $page->getURL(), 'lng' => $lng),'content');
                break;
        }

        $this->_helper->redirector->gotoSimple('error', 'error');

    }
    
    public function submenuAction()
    {
        $system = new Zend_Session_Namespace('System');
        if (false == ($page = $this->_getParam('page'))) {
            return;
        }

        if (!$page->getId()) {
            return;
        }

        if ($page->getParentID() == 0) {
            $mapper = new Infinite_Content_DataMapper();
            $submenus  = $mapper->getChildrenByParentId($page->getId(),$system->lng);
        } else {
            $mapper = new Infinite_Content_DataMapper();
            $submenus  = $mapper->getChildrenByParentId($page->getParentID(),$system->lng);

            /* Titel van hoofdpagina */
            $mapper = new Infinite_Content_DataMapper();
            $parent = $mapper->getByID($page->getParentID(),$system->lng);
            $this->view->pageTitle = $parent->getTitle();
        }


        $this->view->submenus = $submenus;

        $this->view->lng = $system->lng;
        $this->view->currentContentID = $page->getId();
    }
    
    public function footermenuAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $system = new Zend_Session_Namespace('System');
        $contentNamespace = new Zend_Session_Namespace('contentNamespace');
        if (isset($contentNamespace->page)) {
            unset($contentNamespace->page);
        }

        $mapper = new Infinite_Content_DataMapper();
        $nav = $mapper->getTree($system->lng);

        $pages = array();

        foreach($nav as $menu) {
            /*
            $mapper = new Infinite_Content_DataMapper();
            $page = $mapper->getByID($menu->getID(),$system->lng);
            if ($page == $this->_getParam('page')) {
                $page->setActive(true);
            }
            */

            if (!is_array($menu->getChildren()) ) {
                $mapper = new Infinite_Content_DataMapper();
                $page = $mapper->getByID($menu->getID(),$system->lng);
                if ($page == $this->_getParam('page')) {
                    $page->setActive(true);
                }
            } else {
                $mapper = new Infinite_Content_DataMapper();
                $page = $mapper->getByID($menu->getID(),$system->lng);
                if ($page == $this->_getParam('page')) {
                    $page->setActive(true);
                }

                foreach($menu->getChildren() as $submenu) {
                    $mapper = new Infinite_Content_DataMapper();
                    $child = $mapper->getByID($submenu->getID(),$system->lng);
                    if ($child == $this->_getParam('page')) {
                        $page->setActive(true);
                    }
                    $page->addChild($child);
                }
            }

            // Nagaan welke groepen rechten hebben om deze pagina te bekijken
            $mapper = new Infinite_ContentAcl_DataMapper();
            $acls = $mapper->getByContentID($menu->getID());
            $page->setPermissions($acls);

            if($page->isAllowed($identity)) {
                $pages[] = $page;
            }

        }

        $this->view->pages = $pages;

    }

    public function topmenuAction()
    {

    }

}