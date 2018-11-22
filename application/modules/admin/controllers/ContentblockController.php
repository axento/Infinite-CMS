<?php
class Admin_ContentblockController extends Zend_Controller_Action
{
	protected $_block;
	
    public function init()
	{
                    
		$config = Zend_Registry::get('config');
		$lngs = $config->system->language;
		$this->view->lngs = $lngs;

        Infinite_Acl::checkAcl('block');

        $this->view->contentblockTab = 'active';
	}

	public function indexAction()
	{

        $mapper = new Infinite_Contentblock_DataMapper();
        $blocks  = $mapper->getAll($_SESSION['System']['lng']);
        $this->view->blocks = $blocks;
	}

    public function editAction()
	{
		$id = $this->_getParam('id');
        $mapper = new Infinite_Contentblock_DataMapper();
        $block = $mapper->getById($id,$_SESSION['System']['lng']);

		if ($this->getRequest()->isPost()) {
            
            $block->setTitle($this->_getParam('title'))
                    ->setContent($this->_getParam('content'));
            
            $validator = new Infinite_Contentblock_Validator();
			if ($validator->validate($block)) {
				$block->save();

				$flashMessenger = $this->_helper->getHelper('FlashMessenger');
				$flashMessenger->addMessage('Het tekstblok werd succesvol aangepast!');

				$this->_helper->redirector->gotoSimple('index', 'contentblock');
			}
		}

		$this->view->block  = $block;
		$this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Contentblock');
	}

}
