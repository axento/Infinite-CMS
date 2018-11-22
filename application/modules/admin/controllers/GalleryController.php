<?php
class Admin_GalleryController extends Zend_Controller_Action {
    protected $_album;

    public function init() {
        Infinite_Acl::checkAcl('gallery');
        $this->view ->placeholder('modules')
                    ->append('active');
        $system = new Zend_Session_Namespace('System');
        $this->view->galleryTab = 'active';
        $config = Zend_Registry::get('config');
        $lngs = $config->system->language;
        $this->view->lngs = $lngs;
    }

    public function indexAction()
    {
        $systemNamespace = new Zend_Session_Namespace('System');
        $mapper = new Infinite_Gallery_DataMapper();
        $galleries  = $mapper->getAll($systemNamespace->lng);
        $this->view->albums = $galleries;
    }

    public function activateAlbumAction() {
        $id = $this->_getParam('id');
        $mapper = new Infinite_Gallery_DataMapper();
        $gallery = $mapper->getById($id);
        $gallery->activate();

        $flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $flashMessenger->addMessage('De status van het album werd succesvol aangepast!');
        $this->_helper->redirector->gotoSimple('index', 'gallery');
    }

    public function addAction() {

        if ($this->getRequest()->isGet()) {
            $this->_album = new Infinite_Gallery();
        }

        if ($this->getRequest()->isPost()) {

            $this->_album = new Infinite_Gallery();
            $this->_album->setActive(0)
                    ->setTitle($this->_getParam('title'))
                    ->setSummary($this->_getParam('summary'))
                    ->setContent('')
                    ->setSeoTags('')
                    ->setSeoTitle('')
                    ->setSeoDescription('')
                    ->setActive($this->_getParam('active'));

            /* validate post */
            $validator = new Infinite_Gallery_InsertValidator();
            if ($validator->validate($this->_album)) {
                $this->_album->save();

                $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                $flashMessenger->addMessage('Het album werd succesvol aangemaakt!');

                $this->_helper->redirector->gotoSimple('index', 'gallery');
            }
        }

        $this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Gallery');
        $this->view->album = $this->_album;
    }

    public function editAction()
	{
		$id = $this->_getParam('id');
        $mapper = new Infinite_Gallery_DataMapper();
        $album = $mapper->getById($id);

		if ($this->getRequest()->isPost()) {
            $this->_album = $album;

            $this->_album->setTitle($this->_getParam('title'))
                    ->setSummary($this->_getParam('summary'))
                    ->setContent($this->_getParam('content'))
                    ->setSeoTags($this->_getParam('seotags'))
                    ->setSeoTitle($this->_getParam('seotitle'))
                    ->setSeoDescription($this->_getParam('seodescription'))
                    ->setActive($this->_getParam('active'));
                    
            $validator = new Infinite_Gallery_UpdateValidator();
            if ($validator->validate($this->_album)) {
				$this->_album->save();

				$flashMessenger = $this->_helper->getHelper('FlashMessenger');
				$flashMessenger->addMessage('Het album werd succesvol aangepast!');

				$this->_helper->redirector->gotoSimple('index', 'gallery');
			}
		}

		$this->view->album  = $album;
		$this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Gallery');
	}

    public function deleteAction()
	{
        $id = $this->_getParam('id');
        $mapper = new Infinite_Gallery_DataMapper();
        $gallery = $mapper->getById($id);
		$gallery->delete();
        
        /* Verwijder ook alle foto's van dit album */
        $dir = new Axento_Dir();
        $album = APPLICATION_ROOT . '/www/img/gallery/album_' . $id;
        $dir->deleteDir($album);

		$flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$flashMessenger->addMessage('Het album werd succesvol verwijderd!');

		$this->_helper->redirector->gotoSimple('index', 'gallery');
	}

}