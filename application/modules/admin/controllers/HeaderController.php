<?php

class Admin_HeaderController extends Zend_Controller_Action
{
    protected $_header;

    public function init()
    {
        Infinite_Acl::checkAcl('header');
        $this->view ->placeholder('modules')
            ->append('active');
        //$system = new Zend_Session_Namespace('System');
        $this->view->headerTab = 'active';
        $config = Zend_Registry::get('config');
        $lngs = $config->system->language;
        $this->view->lngs = $lngs;
    }

    public function indexAction()
    {
        $systemNamespace = new Zend_Session_Namespace('System');
        $mapper = new Infinite_Header_DataMapper();
        $headers  = $mapper->getAll($systemNamespace->lng);
        $this->view->headers = $headers;
    }

    public function activateAction()
    {
        $id = $this->_getParam('id');
        $mapper = new Infinite_Header_DataMapper();
        $header = $mapper->getById($id);
        $header->activate();

        $flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $flashMessenger->addMessage('De status van de header werd succesvol aangepast!');
        $this->_helper->redirector->gotoSimple('index', 'header');
    }

    public function addAction() {

        /* Config */
        $config = Zend_Registry::get('config');
        $this->view->width = $config->header->large->width;
        $this->view->height = $config->header->large->height;
        $this->view->filesize = $config->header->filesize; // Max image size

        if ($this->getRequest()->isGet()) {
            $header = new Infinite_Header();
        }

        if ($this->getRequest()->isPost()) {

            $systemNamespace = new Zend_Session_Namespace('System');
            $header = new Infinite_Header();

            $uploadPath		= APPLICATION_ROOT . '/'.$config->public->folder.'/img/header';

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

            $basePath = APPLICATION_ROOT . '/'.$config->public->folder.'/img/header';
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
                $image->thumbnailimage($config->header->small->width,$config->header->small->height,true);
                $image->setimageformat('jpg');
                $image->writeimage($basePath . '/small/' . basename($nFname));

                $image = new Imagick($nFname);
                $image->thumbnailimage($config->header->large->width,$config->header->large->height,true);
                $image->setimageformat('jpg');
                $image->writeimage($basePath . '/large/' . basename($nFname));

                $original = APPLICATION_ROOT . '/'.$config->public->folder.'/img/header/' . basename($nFname);
                if (file_exists($original)) { unlink($original); }

            }

            $header->setLng($systemNamespace->lng)
                ->setName($this->_getParam('name'))
                ->setTitle($this->_getParam('title'))
                ->setSubtitle($this->_getParam('subtitle'))
                ->setContent($this->_getParam('content'))
                ->setURL($this->_getParam('url'))
                ->setTextColor($this->_getParam('textColor'))
                ->setXTitle($this->_getParam('xTitle'))
                ->setYTitle($this->_getParam('yTitle'))
                ->setXContent($this->_getParam('xContent'))
                ->setYContent($this->_getParam('yContent'))
                ->setImage(basename($nFname));
//Zend_Debug::dump($header);die;
            /* validate post */
            $validator = new Infinite_Header_InsertValidator();
            if ($validator->validate($header)) {
                $header->save();

                $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                $flashMessenger->addMessage('De header werd succesvol aangemaakt!');

                $this->_helper->redirector->gotoSimple('index', 'header');
            }
        }

        $this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Header');
        $this->view->item = $header;

    }

    public function editAction()
    {
        /* Config */
        $config = Zend_Registry::get('config');
        $this->view->width = $config->header->width;
        $this->view->filesize = $config->header->filesize; // Max image size

        $id = $this->_getParam('id');
        $systemNamespace = new Zend_Session_Namespace('System');

        if ($this->getRequest()->isGet()) {
            $mapper = new Infinite_Header_DataMapper();
            $header = $mapper->getById($id);
        }

        if ($this->getRequest()->isPost()) {

            $mapper = new Infinite_Header_DataMapper();
            $header = $mapper->getById($id);

            $uploadPath		= APPLICATION_ROOT . '/'.$config->public->folder.'/img/header';

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

            $basePath = APPLICATION_ROOT . '/'.$config->public->folder.'/img/header';

            $files = $uploadAdapter->getFilename(null, false);
            if(!is_array($files)) { $files = array($files); }

            foreach ($files as $filename) {

                $oFname = $uploadPath . '/' . $filename;

                if (!$oFname) { continue; }

                $ext    = '.' . strtolower(substr(strrchr($oFname, '.'), 1));
                $nFname = $basePath . '/' . md5(time() . $oFname) . $ext;

                rename($oFname,  $nFname);

                $image = new Imagick($nFname);
                $image->thumbnailimage($config->header->small->width,$config->header->small->height,true);
                $image->setimageformat('jpg');
                $image->writeimage($basePath . '/small/' . basename($nFname));

                $image = new Imagick($nFname);
                $image->thumbnailimage($config->header->large->width,$config->header->large->height,true);
                $image->setimageformat('jpg');
                $image->writeimage($basePath . '/large/' . basename($nFname));

                $original = APPLICATION_ROOT . '/'.$config->public->folder.'/img/header/' . basename($nFname);
                if (file_exists($original)) { unlink($original); }

                $header->setImage(basename($nFname));

            }

            $header->setID($id)
                ->setName($this->_getParam('name'))
                ->setTitle($this->_getParam('title'))
                ->setSubtitle($this->_getParam('subtitle'))
                ->setContent($this->_getParam('content'))
                ->setTextColor($this->_getParam('textColor'))
                ->setURL($this->_getParam('url'))
                ->setXTitle($this->_getParam('xTitle'))
                ->setYTitle($this->_getParam('yTitle'))
                ->setXContent($this->_getParam('xContent'))
                ->setYContent($this->_getParam('yContent'));

            $validator = new Infinite_Header_UpdateValidator();
            if ($validator->validate($header)) {
                $header->save();

                $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                $flashMessenger->addMessage('De gegevens werden succesvol aangepast!');

                $this->_helper->redirector->gotoSimple('index', 'header');
            }
        }

        $this->view->item  = $header;
        $this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Header');
    }

    public function deleteAction()
    {
        $config = Zend_Registry::get('config');
        $id = $this->_getParam('id');
        $mapper = new Infinite_Header_DataMapper();
        $header = $mapper->getById($id);
        $header->delete();

        $image = APPLICATION_ROOT . '/'.$config->public->folder.'/img/header/' . $header->getImage();
        if (file_exists($image)) { unlink($image); }

        $flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $flashMessenger->addMessage('De header werd succesvol verwijderd!');

        $this->_helper->redirector->gotoSimple('index', 'header');
    }

    public function orderAction()
    {
        $systemNamespace = new Zend_Session_Namespace('System');
        $mapper = new Infinite_Header_DataMapper();
        $headers  = $mapper->getAll($systemNamespace->lng);
        $this->view->headers = $headers;

    }

    public function setOrderAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $sort_order = $this->_getParam('sort_order');
        $ranking = explode(',',$sort_order);

        foreach($ranking as $key => $headerID) {
            $header = new Infinite_Header();
            $header->setID($headerID)
                ->setRanking($key+1);
            $header->rank();
        }

    }


}
