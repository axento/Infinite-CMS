<?php

function XML2Array(SimpleXMLElement $parent)
{
    $array = array();

    foreach ($parent as $name => $element) {
        ($node = & $array[$name])
        && (1 === count($node) ? $node = array($node) : 1)
        && $node = & $node[];

        $node = $element->count() ? XML2Array($element) : trim($element);
    }

    return $array;
}

class Admin_ContentController extends Zend_Controller_Action
{

    public function init()
    {
        Infinite_Acl::checkAcl('content');

        $system = new Zend_Session_Namespace('System');
        $contentNamespace = new Zend_Session_Namespace('contentNamespace');
        if (!isset($contentNamespace->page)) {
            $contentNamespace->page = new Infinite_Content();
            $contentNamespace->page->setLanguage($system->lng);
        }

        $this->_page = $contentNamespace->page;

        $config = Zend_Registry::get('config');
        $lngs = $config->system->language;
        $this->view->lngs = $lngs;

        $this->view->contentTab = 'active';
    }

    public function indexAction()
    {
        $system = new Zend_Session_Namespace('System');
        $contentNamespace = new Zend_Session_Namespace('contentNamespace');
        if (isset($contentNamespace->page)) {
            unset($contentNamespace->page);
        }

        $mapper = new Infinite_Content_DataMapper();
        $nav = $mapper->getTree($system->lng);

        $pages = array();
        foreach ($nav as $menu) {
            if (!is_array($menu->getChildren())) {
                $mapper = new Infinite_Content_DataMapper();
                $page = $mapper->getByID($menu->getID(),$system->lng);
            } else {
                $mapper = new Infinite_Content_DataMapper();
                $page = $mapper->getByID($menu->getID(),$system->lng);

                foreach($menu->getChildren() as $submenu) {
                    $mapper = new Infinite_Content_DataMapper();
                    $child = $mapper->getByID($submenu->getID(),$system->lng);
                    $page->addChild($child);
                }
            }

            // Nagaan welke groepen rechten hebben om deze pagina te bekijken
            $mapper = new Infinite_ContentAcl_DataMapper();
            $acls = $mapper->getByContentID($menu->getID());
            $page->setPermissions($acls);

            $pages[] = $page;

        }

        $this->view->pages = $pages;
    }

    public function addAction()
    {

        $system = new Zend_Session_Namespace('System');
        $contentNamespace = new Zend_Session_Namespace('contentNamespace');
        $contentNamespace->page->setNavigation(true);
        $contentNamespace->page->setLayout('default');

        /* Config */
        $config = Zend_Registry::get('config');
        $this->view->width = $config->content->header->width;
        $this->view->height = $config->content->header->height;
        $this->view->filesize = $config->content->header->filesize; // Max image size

        if ($this->_getParam('type') == 'page' ) {
            $contentType = 'page';
            $this->view->pagetype = $contentType;
        } elseif ($this->_getParam('type') == 'module' ) {
            $contentType = 'module';
            $this->view->pagetype = $contentType;
        } elseif ($this->_getParam('type') == 'link' ) {
            $contentType = 'link';
            $this->view->pagetype = $contentType;
        }

        $mapper = new Infinite_Group_DataMapper();
        $this->view->groups = $mapper->getAll();

        $mapper = new Infinite_Template_DataMapper();
        $this->view->templates = $mapper->getAllActive();

        // Haal alle pagina's op die juist onder de homepage vallen
        $mapper = new Infinite_Content_DataMapper();
        $pages = $mapper->getMain();
        $this->view->pages = $pages;

        if ($this->getRequest()->isPost()) {

            $system->lng = $this->_getParam('lang');

            $contentNamespace->page->clearPermissions();
            if ($this->_getParam('acl') == '1') {
                foreach ((array) $this->_getParam('group') as $groupId) {
                    $group = new Infinite_Group();
                    $group->setId($groupId);
                    $contentNamespace->page->addPermission($group);
                }
            }

            // Set Image
            $path = APPLICATION_ROOT . '/www/img/content/';
            $adapter = new Zend_File_Transfer_Adapter_Http();

            $adapter->setDestination($path);
            $adapter->setOptions(array('ignoreNoFile' => true));

            if (!$adapter->receive()) {
                $msgr = Infinite_ErrorStack::getInstance('Infinite_Page');
                $msgr->addMessage('file', $adapter->getMessages(), 'file');
            }

            $filename = '';
            $files = $adapter->getFileInfo();
            foreach ($files as $file) {
                if (!$file['tmp_name']) {
                    continue;
                }

                $filename = $contentNamespace->page->createThumbName($file['name']) . '_' . time() .'.jpg';
                $magicianObj = new imageLib($file['tmp_name']);
                //$magicianObj->resizeImage(65, 1000, 'landscape');
                $magicianObj->saveImage($path . $filename, 100);

                unlink($file['tmp_name']);


            }

            // Set ranking
            $parentID = (int) $this->_getParam('parent_id');
            $mapper = new Infinite_Content_DataMapper();
            $highestRank = $mapper->getHighestRank($parentID);
            if ($highestRank) {
                $ranking = (int) $highestRank->getRanking() + 1;
            } else {
                $ranking = 1;
            }

            $contentNamespace->page->setParentID($parentID)
                ->setContentType($contentType)
                ->setLink($this->_getParam('link'))
                ->setTitle($this->_getParam('title'))
                ->setSubtitle($this->_getParam('subtitle'))
                ->setContent($this->_getParam('contenti'))
                ->setLayout($this->_getParam('layout', 'default'))
                ->setURL($this->_getParam('url'))
                ->setNavigation($this->_getParam('menu'), false)
                ->setRanking($ranking)
                ->setGalleries($this->_getParam('galleries'))
                ->setImage($filename)
                ->setVideo($this->_getParam('video'))
                ->setSeoTitle($this->_getParam('seotitle'))
                ->setSeoTags($this->_getParam('seotags'))
                ->setSeoDescription($this->_getParam('seodescription'));

            $validator = new Infinite_Content_InsertValidator();
            if ($validator->validate($contentNamespace->page)) {

                $config = Zend_Registry::get('config');
                if ($contentNamespace->page->getId() === false) {
                    $lngs = $config->system->language;
                } else {
                    $lngs[$contentNamespace->page->getLanguage()] = null;
                }

                $contentNamespace->page->save();
                $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                $flashMessenger->addMessage('De nieuwe pagina werd succesvol opgslagen!');
                $this->_helper->redirector->gotoSimple('index', 'content');
            }

        }

        /* Haal fotogalerijen op */
        $mapper = new Infinite_Gallery_DataMapper();
        $this->view->galleries = $mapper->getAll($system->lng);

        /* Haal menukaarten op */
        $mapper = new Infinite_Menu_DataMapper();
        $this->view->menus = $mapper->getAll();

        $this->view->page     = $contentNamespace->page;
        $this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Content');
    }

    public function editAction() {

        $system = new Zend_Session_Namespace('System');

        /* Config */
        $config = Zend_Registry::get('config');
        $this->view->width = $config->content->header->width;
        $this->view->height = $config->content->header->height;
        $this->view->filesize = $config->content->header->filesize; // Max image size

        $id = $this->_getParam('id');
        $this->view->currentPage = $id;
        $mapper = new Infinite_Content_DataMapper();
        $page  = $mapper->getById($id, $this->_getParam('lng'));
        $this->view->pagetype = $page->getContentType();


        // Haal alle pagina's op die juist onder de homepage vallen
        $mapper = new Infinite_Content_DataMapper();
        $pages = $mapper->getMain();
        $this->view->pages = $pages;

        $mapper = new Infinite_Group_DataMapper();
        $this->view->groups = $mapper->getAll();

        $mapper = new Infinite_Template_DataMapper();
        $this->view->templates = $mapper->getAllActive();

        if ($this->getRequest()->isPost()) {

            $system->lng = $this->_getParam('lang');

            $page->clearPermissions();
            if ($this->_getParam('acl') == '1') {
                foreach ((array) $this->_getParam('group') as $groupId) {
                    $group = new Infinite_Group();
                    $group->setId($groupId);
                    $page->addPermission($group);
                }
            }

            // Set Image
            $path = APPLICATION_ROOT . '/www/img/content/';
            $adapter = new Zend_File_Transfer_Adapter_Http();

            $adapter->setDestination($path);
            $adapter->setOptions(array('ignoreNoFile' => true));

            if (!$adapter->receive()) {
                $msgr = Infinite_ErrorStack::getInstance('Infinite_Page');
                $msgr->addMessage('file', $adapter->getMessages(), 'file');
            }

            $files = $adapter->getFileInfo();
            foreach ($files as $file) {
                if (!$file['tmp_name']) {
                    continue;
                }

                $filename = $page->createThumbName($file['name']) . '_' . time() .'.jpg';
                $magicianObj = new imageLib($file['tmp_name']);
                //$magicianObj->resizeImage(65, 85, 'crop');
                $magicianObj->saveImage($path . $filename, 100);

                unlink($file['tmp_name']);

                $page->setImage($filename);
            }

            $page->setID($id)
                ->setLanguage($system->lng)
                ->setParentID((int) $this->_getParam('parent_id'))
                ->setLink($this->_getParam('link'))
                ->setTitle($this->_getParam('title'))
                ->setSubtitle($this->_getParam('subtitle'))
                ->setContent($this->_getParam('contenti'))
                ->setLayout($this->_getParam('layout', 'default'))
                ->setURL($this->_getParam('url'))
                ->setNavigation($this->_getParam('menu'), false)
                ->setGalleries($this->_getParam('galleries'))
                ->setImage($filename)
                ->setVideo($this->_getParam('video'))
                ->setSeoTitle($this->_getParam('seotitle'))
                ->setSeoTags($this->_getParam('seotags'))
                ->setSeoDescription($this->_getParam('seodescription'));

            $validator = new Infinite_Content_UpdateValidator();
            if ($validator->validate($page)) {
                $page->update();
                $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                $flashMessenger->addMessage('De pagina werd succesvol aangepast!');
                $this->_helper->redirector->gotoSimple('index', 'content');
            }

        }

        /* Haal fotogalerijen op */
        $mapper = new Infinite_Gallery_DataMapper();
        $this->view->galleries = $mapper->getAll($system->lng);

        /* Haal menukaarten op */
        $mapper = new Infinite_Menu_DataMapper();
        $this->view->menus = $mapper->getAll();


        $this->view->page = $page;
        $this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Content');
    }

    public function deleteAction()
    {

        $page = new Infinite_Content();
        $page->setId($this->_getParam('id'));
        $page->delete($page);
        
        $flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $flashMessenger->addMessage('De pagina werd succesvol verwijderd!');
        
        $this->_helper->redirector->gotoSimple('index', 'content');
    }

    public function getUrlAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $system = new Zend_Session_Namespace('System');

        $this->_page->setTitle($this->_getParam('title'));

        if ($this->_getParam('parentID') != 0) {
            $mapper = new Infinite_Content_DataMapper();
            $parent = $mapper->getByID($this->_getParam('parentID'), $system->lng);
            $parent_url = $parent->getURL();
            $link = $parent_url . '/' . $this->_page->createUrl();
        } else {
            $link = $this->_page->createUrl();
        }

        $router = Zend_Controller_Front::getInstance()->getRouter();
        $link = $router->assemble(array('url' => $link, 'lng' => $system->lng), 'content');

        echo urldecode($link);
    }

    public function orderAction()
    {
        $system = new Zend_Session_Namespace('System');

        $mapper = new Infinite_Content_DataMapper();
        $nav = $mapper->getTree($system->lng);
        $this->view->items = $nav;
    }

    public function subOrderAction()
    {
        $system = new Zend_Session_Namespace('System');
        $parentID = $this->_getParam('id');

        $mapper = new Infinite_Content_DataMapper();
        $nav = $mapper->getChildrenByParentId($parentID,$system->lng);
        $this->view->items = $nav;
    }

    public function setOrderAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $sort_order = $this->_getParam('sort_order');
        $ranking = explode(',',$sort_order);

        foreach($ranking as $key => $pageID) {
            $page = new Infinite_Content();
            $page->setID($pageID)
                 ->setRanking($key+1);
            $page->rank();
        }

        // maak cachegeheugen leeg
        $cache = Zend_Registry::get('cache');
        $cache->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_TAG,
            array('Infinite_Content')
        );

    }

    public function setOrderSubmenuAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $sort_order = $this->_getParam('sort_order');
        $ranking = explode(',',$sort_order);

        foreach($ranking as $key => $pageID) {
            $page = new Infinite_Content();
            $page->setID($pageID)
                ->setRanking($key+1);
            $page->rank();
        }

        // maak cachegeheugen leeg
        $cache = Zend_Registry::get('cache');
        $cache->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_TAG,
            array('Infinite_Content')
        );

    }

}
