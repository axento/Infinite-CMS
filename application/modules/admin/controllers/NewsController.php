<?php

class Admin_NewsController extends Zend_Controller_Action
{
	protected $_item;

	public function init()
	{
        Infinite_Acl::checkAcl('news');
        $this->view ->placeholder('modules')
                    ->append('active');
        //$system = new Zend_Session_Namespace('System');
        $this->view->newsTab = 'active';
		$config = Zend_Registry::get('config');
		$lngs = $config->system->language;
		$this->view->lngs = $lngs;
	}

    public function indexAction()
	{
        $systemNamespace = new Zend_Session_Namespace('System');
        $mapper = new Infinite_News_DataMapper();
        $news  = $mapper->getAllNews($systemNamespace->lng);
        $this->view->news = $news;
	}

    public function activateAction()
    {
    	$id = $this->_getParam('id');
        $mapper = new Infinite_News_DataMapper();
        $news = $mapper->getById($id);
        $news->activate();
        
        $flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$flashMessenger->addMessage('De status van het nieuwsbericht werd succesvol aangepast!');
    	$this->_helper->redirector->gotoSimple('index', 'news');
    }

    public function addAction()
	{
        /* Config */
        $config = Zend_Registry::get('config');
        $this->view->width = $config->image->large->width;
        $this->view->height = $config->image->large->height;
        $this->view->filesize = $config->image->filesize; // Max image size

	    $systemNamespace = new Zend_Session_Namespace('System');

        $mapper = new Infinite_NewsTag_DataMapper();
        $tags = $mapper->getAll();
        $this->view->tags = $tags;
        //Zend_Debug::dump($tags);

		//if ($this->getRequest()->isGet()) {
			$news = new Infinite_News();
            $news->setTID(1);
            $news->setDatePublication(date("Y-m-d H:i:s", time()));
            $news->setLayout('full');
		//}

		if ($this->getRequest()->isPost()) {

            $news = new Infinite_News();
            if ($this->_getParam('archive') == '1') {
                $archive = 1;
            } else {
                $archive = 0;
            }

            $datePublication = substr($this->_getParam('datePublication'),6,4).'-'.
                substr($this->_getParam('datePublication'),3,2).'-'.
                substr($this->_getParam('datePublication'),0,2).' '.
                substr($this->_getParam('timePublication'),0,2).':'.
                substr($this->_getParam('timePublication'),3,2).':00';

            $uploadPath		= APPLICATION_ROOT . '/'.$config->public->folder.'/img/news';

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

            $basePath = APPLICATION_ROOT . '/'.$config->public->folder.'/img/news';
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

			$news->setTitle($this->_getParam('title'))
                         ->setSummary($this->_getParam('summary'))
                         ->setContent($this->_getParam('content'))
						 ->setGalleries($this->_getParam('galleries'))
                         ->setViews(0)
                         ->setArchive($archive)
                         ->setSeoTags($this->_getParam('seotags'))
                         ->setSeoTitle($this->_getParam('seotitle'))
                         ->setSeoDescription($this->_getParam('seodescription'))
                         ->setActive(1)
                         ->setTid($this->_getParam('tagID'))
                         ->setDatePublication($datePublication)
                         ->setLayout($this->_getParam('layout'))
                         ->setVideo($this->_getParam('video'))
                         ->setImage(basename($nFname));
//Zend_Debug::dump($news);die;
			/* validate post */
            $validator = new Infinite_News_InsertValidator();
			if ($validator->validate($news)) {
				$news->save();

				$flashMessenger = $this->_helper->getHelper('FlashMessenger');
				$flashMessenger->addMessage('Het nieuwsbericht werd succesvol aangemaakt!');

				$this->_helper->redirector->gotoSimple('index', 'news');
			}
		}

		$mapper = new Infinite_Gallery_DataMapper();
		$this->view->galleries = $mapper->getAll($systemNamespace->lng);

		$this->view->day = substr($news->getDatePublication(),8,2);
        $this->view->month = substr($news->getDatePublication(),5,2);
        $this->view->year = substr($news->getDatePublication(),0,4);
        $this->view->hour = substr($news->getDatePublication(),11,2);
        $this->view->minutes = substr($news->getDatePublication(),14,2);

		$this->view->errors = Infinite_ErrorStack::getInstance('Infinite_News');
		$this->view->news = $news;
		//Zend_Debug::dump($news);
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

        $mapper = new Infinite_NewsTag_DataMapper();
        $tags = $mapper->getAll();
        $this->view->tags = $tags;

		if ($this->getRequest()->isGet()) {
        	$mapper = new Infinite_News_DataMapper();
        	$news = $mapper->getById($id);
		}

		if ($this->getRequest()->isPost()) {
			
			$mapper = new Infinite_News_DataMapper();
        	$news = $mapper->getById($id);
            if ($this->_getParam('archive') == '1') {
                $archive = 1;
            } else {
                $archive = 0;
            }

            $datePublication = substr($this->_getParam('datePublication'),6,4).'-'.
                substr($this->_getParam('datePublication'),3,2).'-'.
                substr($this->_getParam('datePublication'),0,2).' '.
                substr($this->_getParam('timePublication'),0,2).':'.
                substr($this->_getParam('timePublication'),3,2).':00';

            $uploadPath		= APPLICATION_ROOT . '/'.$config->public->folder.'/img/news';

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

            $basePath = APPLICATION_ROOT . '/'.$config->public->folder.'/img/news';
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

			$news->setLng($systemNamespace->lng)
                ->setTitle($this->_getParam('title'))
                ->setSummary($this->_getParam('summary'))
                ->setContent($this->_getParam('content'))
                ->setGalleries($this->_getParam('galleries'))
                ->setViews(0)
                ->setArchive($archive)
                ->setSeoTags($this->_getParam('seotags'))
                ->setSeoTitle($this->_getParam('seotitle'))
                ->setSeoDescription($this->_getParam('seodescription'))
                ->setActive(1)
                ->setTid($this->_getParam('tagID'))
                ->setDatePublication($datePublication)
                ->setLayout($this->_getParam('layout'))
                ->setVideo($this->_getParam('video'))
                ->setImage(basename($nFname));
//Zend_Debug::dump($news);die;
            $validator = new Infinite_News_UpdateValidator();
            if ($validator->validate($news)) {
				$news->save();

				$flashMessenger = $this->_helper->getHelper('FlashMessenger');
				$flashMessenger->addMessage('Het nieuwsbericht werd succesvol aangepast!');

				$this->_helper->redirector->gotoSimple('index', 'news');
			}
		}

		$mapper = new Infinite_Gallery_DataMapper();
		$this->view->galleries = $mapper->getAll($systemNamespace->lng);

        $this->view->day = substr($news->getDatePublication(),8,2);
        $this->view->month = substr($news->getDatePublication(),5,2);
        $this->view->year = substr($news->getDatePublication(),0,4);
        $this->view->hour = substr($news->getDatePublication(),11,2);
        $this->view->minutes = substr($news->getDatePublication(),14,2);

		$this->view->news  = $news;

		$this->view->errors = Infinite_ErrorStack::getInstance('Infinite_News');
	}

    public function deleteAction()
	{
        $config = Zend_Registry::get('config');
	    $id = $this->_getParam('id');
        $mapper = new Infinite_News_DataMapper();
        $news = $mapper->getById($id);
		$news->delete();

        $small = APPLICATION_ROOT . '/'.$config->public->folder.'/img/news/small/' . $news->getImage();
        $large = APPLICATION_ROOT . '/'.$config->public->folder.'/img/news/large/' . $news->getImage();
        if (file_exists($small)) { unlink($small); }
        if (file_exists($large)) { unlink($large); }

		$flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$flashMessenger->addMessage('Het bericht werd succesvol verwijderd!');

		$this->_helper->redirector->gotoSimple('index', 'news');
	}

}