<?php

class Admin_MerchantTypeController extends Zend_Controller_Action
{

	public function init()
	{
        Infinite_Acl::checkAcl('merchant');
        $this->view ->placeholder('modules')
                    ->append('active');

        $this->view->merchantTypeTab = 'active';
		$config = Zend_Registry::get('config');
		$lngs = $config->system->language;
		$this->view->lngs = $lngs;
	}

    public function indexAction()
	{
        $systemNamespace = new Zend_Session_Namespace('System');
        $mapper = new Infinite_MerchantType_DataMapper();
        $types  = $mapper->getAll($systemNamespace->lng);
        $this->view->types = $types;
	}

    public function addAction()
	{

		if ($this->getRequest()->isGet()) {
			$type = new Infinite_MerchantType();
		}

		if ($this->getRequest()->isPost()) {

            $type = new Infinite_MerchantType();
            $type->setType($this->_getParam('merchantType'));

			/* validate post */
            $validator = new Infinite_MerchantType_InsertValidator();
			if ($validator->validate($type)) {
                $type->save();

				$flashMessenger = $this->_helper->getHelper('FlashMessenger');
				$flashMessenger->addMessage('het handelaars type werd succesvol aangemaakt!');

				$this->_helper->redirector->gotoSimple('index', 'merchant-type');
			}
		}

		$this->view->errors = Infinite_ErrorStack::getInstance('Infinite_MerchantType');
		$this->view->item = $type;
	}

    public function editAction()
	{

		$id = $this->_getParam('id');

		if ($this->getRequest()->isGet()) {
        	$mapper = new Infinite_MerchantType_DataMapper();
        	$type = $mapper->getByID($id);
		}

		if ($this->getRequest()->isPost()) {
			
			$mapper = new Infinite_MerchantType_DataMapper();
            $type = $mapper->getById($id);

            $type->setType($this->_getParam('merchantType'));

            $validator = new Infinite_MerchantType_UpdateValidator();
            if ($validator->validate($type)) {
				$type->save();

				$flashMessenger = $this->_helper->getHelper('FlashMessenger');
				$flashMessenger->addMessage('De gegevens werden succesvol aangepast!');

				$this->_helper->redirector->gotoSimple('index', 'merchant-type');
			}
		}

		$this->view->item  = $type;
		$this->view->errors = Infinite_ErrorStack::getInstance('Infinite_MerchantType');
	}

    public function deleteAction()
	{

	    $id = $this->_getParam('id');
        $mapper = new Infinite_MerchantType_DataMapper();
        $type = $mapper->getByID($id);

        $mapper = new Infinite_Merchant_DataMapper();
        $merchants = $mapper->getByTypeID($id);
        if (!$merchants) {
            $type->delete();
            $flashMessenger = $this->_helper->getHelper('FlashMessenger');
            $flashMessenger->addMessage('Het handelaars type werd succesvol verwijderd!');
        } else {
            $flashMessenger = $this->_helper->getHelper('FlashMessenger');
            $flashMessenger->addMessage('Het type kon niet verwijderd worden!');
        }

		$this->_helper->redirector->gotoSimple('index', 'merchant-type');
	}

}