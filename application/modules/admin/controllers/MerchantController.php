<?php

class Admin_MerchantController extends Zend_Controller_Action
{
	protected $_item;

	public function init()
	{
        Infinite_Acl::checkAcl('merchant');
        $this->view ->placeholder('modules')
                    ->append('active');
        //$system = new Zend_Session_Namespace('System');
        $this->view->merchantTab = 'active';
		$config = Zend_Registry::get('config');
		$lngs = $config->system->language;
		$this->view->lngs = $lngs;
	}

    public function indexAction()
	{
        $systemNamespace = new Zend_Session_Namespace('System');
        $mapper = new Infinite_Merchant_DataMapper();
        $merchants  = $mapper->getAllMerchants($systemNamespace->lng);
        $this->view->merchants = $merchants;
	}

    public function addAction()
	{
        $systemNamespace = new Zend_Session_Namespace('System');

        /* Config */
        $config = Zend_Registry::get('config');
        $this->view->width = $config->image->large->width;
        $this->view->height = $config->image->large->height;
        $this->view->filesize = $config->image->filesize; // Max image size

		if ($this->getRequest()->isGet()) {
			$merchant = new Infinite_Merchant();
		}

		if ($this->getRequest()->isPost()) {

            $merchant = new Infinite_Merchant();

            $uploadPath		= APPLICATION_ROOT . '/'.$config->public->folder.'/img/merchants';

            $uploadAdapter	= new Zend_File_Transfer_Adapter_Http();
            $uploadAdapter->setDestination($uploadPath);
            $uploadAdapter->setOptions(array(
                    'ignoreNoFile' => true
                )
            );

            $extValidator = new Zend_Validate_File_Extension('jpg,png,gif');
            $extValidator->setMessage(
                'Ongeldige foto extensie',
                Zend_Validate_File_Extension::FALSE_EXTENSION
            );

            $uploadAdapter->addValidator($extValidator);
            $uploadAdapter->receive();
            $messages = $uploadAdapter->getMessages();

            if (count($messages)) {
                $this->_helper->layout()->disableLayout();
                $this->view->result = Zend_Json::encode(array('success' => false));
                return;
            }

            $basePath = APPLICATION_ROOT . '/'.$config->public->folder.'/img/merchants';
            $old_umask = umask(0);
            if (!is_dir($basePath)) { mkdir($basePath, 0777, true); }
            if (!is_dir($basePath . '/small')) { mkdir($basePath . '/small', 0777, true); }
            if (!is_dir($basePath . '/large')) { mkdir($basePath . '/large', 0777, true); }
            umask($old_umask);

            $files = $uploadAdapter->getFilename(null, false);
            if(!is_array($files)) { $files = array($files); }

            foreach ($files as $filename) {

                $oFname = $uploadPath . '/' . $filename;

                if (!$oFname) { continue; }

                $ext    = '.' . strtolower(substr(strrchr($oFname, '.'), 1));
                $nFname = $basePath . '/' . md5(time() . $oFname) . $ext;

                rename($oFname,  $nFname);

                $image = new Imagick($nFname);
                $image->thumbnailimage($config->image->small->width,$config->image->small->height,true);
                $image->setimageformat('jpg');
                $image->writeimage($basePath . '/small/' . basename($nFname));

                $image = new Imagick($nFname);
                $image->thumbnailimage($config->image->large->width,$config->image->large->height,true);
                $image->setimageformat('jpg');
                $image->writeimage($basePath . '/large/' . basename($nFname));


                $original = $basePath .'/'. basename($nFname);
                if (file_exists($original)) { unlink($original); }
            }

			$merchant->setShortcut(0)
                ->setTypeID($this->_getParam('typeID'))
                ->setCompany($this->_getParam('company'))
                        ->setContact($this->_getParam('contact'))
                        ->setStreet($this->_getParam('street'))
                        ->setNumber($this->_getParam('number'))
                        ->setBox($this->_getParam('box'))
                        ->setZip($this->_getParam('zip'))
                        ->setCity($this->_getParam('city'))
                        ->setPhone($this->_getParam('phone'))
                        ->setEmail($this->_getParam('email'))
                        ->setWebsite($this->_getParam('website'))
                         ->setContent($this->_getParam('content'))
                         ->setViews(0)
                         ->setSeoTags($this->_getParam('seotags'))
                         ->setSeoTitle($this->_getParam('seotitle'))
                         ->setSeoDescription($this->_getParam('seodescription'))
                         ->setImage(basename($nFname));
//Zend_Debug::dump($merchant);die;
			/* validate post */
            $validator = new Infinite_Merchant_InsertValidator();
			if ($validator->validate($merchant)) {
				$merchant->save();

				$flashMessenger = $this->_helper->getHelper('FlashMessenger');
				$flashMessenger->addMessage('De handelaar werd succesvol aangemaakt!');

				$this->_helper->redirector->gotoSimple('index', 'merchant');
			}
		}

        $mapper = new Infinite_MerchantType_DataMapper();
        $types = $mapper->getAll();
        $this->view->types = $types;

		$this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Merchant');
		$this->view->item = $merchant;
	}

    public function editAction()
	{
        /* Config */
        $config = Zend_Registry::get('config');
        $this->view->width = $config->image->large->width;
        $this->view->height = $config->image->large->height;
        $this->view->filesize = $config->image->filesize; // Max image size

		$id = $this->_getParam('id');
        $systemNamespace = new Zend_Session_Namespace('System');

		if ($this->getRequest()->isGet()) {
        	$mapper = new Infinite_Merchant_DataMapper();
        	$merchant = $mapper->getById($id);
		}

		if ($this->getRequest()->isPost()) {
			
			$mapper = new Infinite_Merchant_DataMapper();
        	$merchant = $mapper->getById($id);

            $uploadPath		= APPLICATION_ROOT . '/'.$config->public->folder.'/img/merchants';

            $uploadAdapter	= new Zend_File_Transfer_Adapter_Http();
            $uploadAdapter->setDestination($uploadPath);
            $uploadAdapter->setOptions(array(
                    'ignoreNoFile' => true
                )
            );

            $extValidator = new Zend_Validate_File_Extension('jpg,png,gif');
            $extValidator->setMessage(
                'Ongeldige foto extensie',
                Zend_Validate_File_Extension::FALSE_EXTENSION
            );

            $uploadAdapter->addValidator($extValidator);
            $uploadAdapter->receive();
            $messages = $uploadAdapter->getMessages();

            if (count($messages)) {
                $this->_helper->layout()->disableLayout();
                $this->view->result = Zend_Json::encode(array('success' => false));
                return;
            }

            $basePath = APPLICATION_ROOT . '/'.$config->public->folder.'/img/merchants';
            $old_umask = umask(0);
            if (!is_dir($basePath)) { mkdir($basePath, 0777, true); }
            if (!is_dir($basePath . '/small')) { mkdir($basePath . '/small', 0777, true); }
            if (!is_dir($basePath . '/large')) { mkdir($basePath . '/large', 0777, true); }
            umask($old_umask);

            $files = $uploadAdapter->getFilename(null, false);
            if(!is_array($files)) { $files = array($files); }

            foreach ($files as $filename) {

                $oFname = $uploadPath . '/' . $filename;

                if (!$oFname) { continue; }

                $ext    = '.' . strtolower(substr(strrchr($oFname, '.'), 1));
                $nFname = $basePath . '/' . md5(time() . $oFname) . $ext;

                rename($oFname,  $nFname);

                $image = new Imagick($nFname);
                $image->thumbnailimage($config->image->small->width,$config->image->small->height,true);
                $image->setimageformat('jpg');
                $image->writeimage($basePath . '/small/' . basename($nFname));

                $image = new Imagick($nFname);
                $image->thumbnailimage($config->image->large->width,$config->image->large->height,true);
                $image->setimageformat('jpg');
                $image->writeimage($basePath . '/large/' . basename($nFname));


                $original = $basePath .'/'. basename($nFname);
                if (file_exists($original)) { unlink($original); }
            }

            $merchant->setTypeID($this->_getParam('typeID'))
                ->setCompany($this->_getParam('company'))
                ->setContact($this->_getParam('contact'))
                ->setStreet($this->_getParam('street'))
                ->setNumber($this->_getParam('number'))
                ->setBox($this->_getParam('box'))
                ->setZip($this->_getParam('zip'))
                ->setCity($this->_getParam('city'))
                ->setPhone($this->_getParam('phone'))
                ->setEmail($this->_getParam('email'))
                ->setWebsite($this->_getParam('website'))
                ->setContent($this->_getParam('content'))
                ->setViews(0)
                ->setSeoTags($this->_getParam('seotags'))
                ->setSeoTitle($this->_getParam('seotitle'))
                ->setSeoDescription($this->_getParam('seodescription'))
                ->setImage(basename($nFname));

            $validator = new Infinite_Merchant_UpdateValidator();
            if ($validator->validate($merchant)) {
				$merchant->save();

				$flashMessenger = $this->_helper->getHelper('FlashMessenger');
				$flashMessenger->addMessage('De gegevens werden succesvol aangepast!');

				$this->_helper->redirector->gotoSimple('index', 'merchant');
			}
		}

        $mapper = new Infinite_MerchantType_DataMapper();
        $types = $mapper->getAll();
        $this->view->types = $types;

		$this->view->item  = $merchant;
		$this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Merchant');
	}

    public function addLinkAction()
    {
        $systemNamespace = new Zend_Session_Namespace('System');

        if ($this->getRequest()->isGet()) {
            $merchant = new Infinite_Merchant();
        }

        if ($this->getRequest()->isPost()) {

            $merchant = new Infinite_Merchant();

            $merchant->setTypeID($this->_getParam('typeID'))
                ->setCompany($this->_getParam('company'))
                ->setWebsite($this->_getParam('website'))
                ->setShortcut(1)
                ->setViews(0);
//Zend_Debug::dump($merchant);die;
            /* validate post */
            $validator = new Infinite_Merchant_InsertValidator();
            if ($validator->validateShortcut($merchant)) {
                $merchant->save();

                $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                $flashMessenger->addMessage('De handelaar werd succesvol aangemaakt!');

                $this->_helper->redirector->gotoSimple('index', 'merchant');
            }
        }

        $mapper = new Infinite_MerchantType_DataMapper();
        $types = $mapper->getAll();
        $this->view->types = $types;

        $this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Merchant');
        $this->view->item = $merchant;
    }

    public function editLinkAction()
    {
        $id = $this->_getParam('id');

        if ($this->getRequest()->isGet()) {
            $mapper = new Infinite_Merchant_DataMapper();
            $merchant = $mapper->getById($id);
        }

        if ($this->getRequest()->isPost()) {

            $mapper = new Infinite_Merchant_DataMapper();
            $merchant = $mapper->getById($id);

            $merchant->setID($id)
                ->setTypeID($this->_getParam('typeID'))
                ->setCompany($this->_getParam('company'))
                ->setWebsite($this->_getParam('website'));

            /* validate post */
            $validator = new Infinite_Merchant_UpdateValidator();
            if ($validator->validateShortcut($merchant)) {
                $merchant->save();

                $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                $flashMessenger->addMessage('De gegevens werden succesvol aangepast!');

                $this->_helper->redirector->gotoSimple('index', 'merchant');
            }
        }

        $mapper = new Infinite_MerchantType_DataMapper();
        $types = $mapper->getAll();
        $this->view->types = $types;

        $this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Merchant');
        $this->view->item = $merchant;

    }

    public function deleteAction()
	{
        $config = Zend_Registry::get('config');
	    $id = $this->_getParam('id');
        $mapper = new Infinite_Merchant_DataMapper();
        $merchant = $mapper->getById($id);
		$merchant->delete();

        $small = APPLICATION_ROOT . '/'.$config->public->folder.'/img/merchants/small/' . $merchant->getImage();
        $large = APPLICATION_ROOT . '/'.$config->public->folder.'/img/merchants/large/' . $merchant->getImage();
        if (file_exists($small)) { unlink($small); }
        if (file_exists($large)) { unlink($large); }

		$flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$flashMessenger->addMessage('De handelaar werd succesvol verwijderd!');

		$this->_helper->redirector->gotoSimple('index', 'merchant');
	}

}