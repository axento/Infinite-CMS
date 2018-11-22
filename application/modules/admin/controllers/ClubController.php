<?php

class Admin_ClubController extends Zend_Controller_Action
{
	protected $_item;

	public function init()
	{
        Infinite_Acl::checkAcl('club');
        $this->view ->placeholder('modules')
                    ->append('active');
        //$system = new Zend_Session_Namespace('System');
        $this->view->clubTab = 'active';
		$config = Zend_Registry::get('config');
		$lngs = $config->system->language;
		$this->view->lngs = $lngs;
	}

    public function indexAction()
	{
        $systemNamespace = new Zend_Session_Namespace('System');
        $mapper = new Infinite_Club_DataMapper();
        $clubs  = $mapper->getAllClubs($systemNamespace->lng);
        $this->view->clubs = $clubs;
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
			$club = new Infinite_Club();
		}

		if ($this->getRequest()->isPost()) {

            $club = new Infinite_Club();

            $uploadPath		= APPLICATION_ROOT . '/'.$config->public->folder.'/img/clubs';

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

            $basePath = APPLICATION_ROOT . '/'.$config->public->folder.'/img/clubs';
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

			$club->setShortcut(0)
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
//Zend_Debug::dump($club);die;
			/* validate post */
            $validator = new Infinite_Club_InsertValidator();
			if ($validator->validate($club)) {
				$club->save();

				$flashMessenger = $this->_helper->getHelper('FlashMessenger');
				$flashMessenger->addMessage('De vereniging werd succesvol aangemaakt!');

				$this->_helper->redirector->gotoSimple('index', 'club');
			}
		}

		$this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Club');
		$this->view->item = $club;
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
        	$mapper = new Infinite_Club_DataMapper();
        	$club = $mapper->getById($id);
		}

		if ($this->getRequest()->isPost()) {
			
			$mapper = new Infinite_Club_DataMapper();
        	$club = $mapper->getById($id);

            $uploadPath		= APPLICATION_ROOT . '/'.$config->public->folder.'/img/clubs';

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

            $basePath = APPLICATION_ROOT . '/'.$config->public->folder.'/img/clubs';
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

            $club->setCompany($this->_getParam('company'))
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

            $validator = new Infinite_Club_UpdateValidator();
            if ($validator->validate($club)) {
				$club->save();

				$flashMessenger = $this->_helper->getHelper('FlashMessenger');
				$flashMessenger->addMessage('De gegevens werden succesvol aangepast!');

				$this->_helper->redirector->gotoSimple('index', 'club');
			}
		}

		$this->view->item  = $club;
		$this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Club');
	}

    public function addLinkAction()
    {
        $systemNamespace = new Zend_Session_Namespace('System');

        if ($this->getRequest()->isGet()) {
            $club = new Infinite_Club();
        }

        if ($this->getRequest()->isPost()) {

            $club = new Infinite_Club();

            $club->setCompany($this->_getParam('company'))
                ->setWebsite($this->_getParam('website'))
                ->setShortcut(1)
                ->setViews(0);
//Zend_Debug::dump($merchant);die;
            /* validate post */
            $validator = new Infinite_Club_InsertValidator();
            if ($validator->validateShortcut($club)) {
                $club->save();

                $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                $flashMessenger->addMessage('De vereniging werd succesvol aangemaakt!');

                $this->_helper->redirector->gotoSimple('index', 'club');
            }
        }

        $this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Club');
        $this->view->item = $club;
    }

    public function editLinkAction()
    {
        $id = $this->_getParam('id');

        if ($this->getRequest()->isGet()) {
            $mapper = new Infinite_Club_DataMapper();
            $club = $mapper->getById($id);
        }

        if ($this->getRequest()->isPost()) {

            $mapper = new Infinite_Club_DataMapper();
            $club = $mapper->getById($id);

            $club->setID($id)
                ->setCompany($this->_getParam('company'))
                ->setWebsite($this->_getParam('website'));

            /* validate post */
            $validator = new Infinite_Club_UpdateValidator();
            if ($validator->validateShortcut($club)) {
                $club->save();

                $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                $flashMessenger->addMessage('De gegevens werden succesvol aangepast!');

                $this->_helper->redirector->gotoSimple('index', 'club');
            }
        }

        $this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Club');
        $this->view->item = $club;

    }

    public function deleteAction()
	{
        $config = Zend_Registry::get('config');
	    $id = $this->_getParam('id');
        $mapper = new Infinite_Club_DataMapper();
        $club = $mapper->getById($id);
		$club->delete();

        $small = APPLICATION_ROOT . '/'.$config->public->folder.'/img/clubs/small/' . $club->getImage();
        $large = APPLICATION_ROOT . '/'.$config->public->folder.'/img/clubs/large/' . $club->getImage();
        if (file_exists($small)) { unlink($small); }
        if (file_exists($large)) { unlink($large); }

		$flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$flashMessenger->addMessage('De handelaar werd succesvol verwijderd!');

		$this->_helper->redirector->gotoSimple('index', 'club');
	}

}