<?php

class Admin_LogoController extends Zend_Controller_Action
{
	protected $_logo;

    public function init()
    {
        Infinite_Acl::checkAcl('logo');
        $this->view ->placeholder('modules')
            ->append('active');
        //$system = new Zend_Session_Namespace('System');
        $this->view->logoTab = 'active';
        $config = Zend_Registry::get('config');
        $lngs = $config->system->language;
        $this->view->lngs = $lngs;
    }

    public function indexAction()
    {
        $systemNamespace = new Zend_Session_Namespace('System');
        $mapper = new Infinite_Logo_DataMapper();
        $logos  = $mapper->getAll($systemNamespace->lng);
        $this->view->logos = $logos;
    }

    public function activateAction()
    {
        $id = $this->_getParam('id');
        $mapper = new Infinite_Logo_DataMapper();
        $logo = $mapper->getById($id);
        $logo->activate();

        $flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $flashMessenger->addMessage('De status van het logo werd succesvol aangepast!');
        $this->_helper->redirector->gotoSimple('index', 'logo');
    }

    public function addAction() {

        /* Config */
        $config = Zend_Registry::get('config');
        $this->view->width = $config->logo->width;
        $this->view->filesize = $config->logo->filesize; // Max image size

        if ($this->getRequest()->isGet()) {
            $logo = new Infinite_Logo();
        }

        if ($this->getRequest()->isPost()) {

            $logo = new Infinite_Logo();

            $uploadPath		= APPLICATION_ROOT . '/'.$config->public->folder.'/img/logo';

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

            $basePath = APPLICATION_ROOT . '/'.$config->public->folder.'/img/logo';
            $old_umask = umask(0);
            if (!is_dir($basePath)) { mkdir($basePath, 0777, true); }
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
                $image->thumbnailimage($config->logo->width,$config->logo->width,true);
                $image->setimageformat('jpg');
                $image->writeimage($basePath .'/'. basename($nFname));

            }

            $logo->setURL($this->_getParam('url'))
                ->setLogo(basename($nFname));
//Zend_Debug::dump($logo);die;
            /* validate post */
            $validator = new Infinite_Logo_InsertValidator();
            if ($validator->validate($logo)) {
                $logo->save();

                $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                $flashMessenger->addMessage('Het logo werd succesvol aangemaakt!');

                $this->_helper->redirector->gotoSimple('index', 'logo');
            }
        }

        $this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Logo');
        $this->view->item = $logo;
        
    }

    public function editAction()
    {
        /* Config */
        $config = Zend_Registry::get('config');
        $this->view->width = $config->logo->width;
        $this->view->filesize = $config->logo->filesize; // Max image size

        $id = $this->_getParam('id');
        $systemNamespace = new Zend_Session_Namespace('System');

        if ($this->getRequest()->isGet()) {
            $mapper = new Infinite_Logo_DataMapper();
            $logo = $mapper->getById($id);
        }

        if ($this->getRequest()->isPost()) {

            $mapper = new Infinite_Logo_DataMapper();
            $logo = $mapper->getById($id);

            $uploadPath		= APPLICATION_ROOT . '/'.$config->public->folder.'/img/logo';

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

            $basePath = APPLICATION_ROOT . '/'.$config->public->folder.'/img/logo';

            $files = $uploadAdapter->getFilename(null, false);
            if(!is_array($files)) { $files = array($files); }

            foreach ($files as $filename) {

                $oFname = $uploadPath . '/' . $filename;

                if (!$oFname) { continue; }

                $ext    = '.' . strtolower(substr(strrchr($oFname, '.'), 1));
                $nFname = $basePath . '/' . md5(time() . $oFname) . $ext;

                rename($oFname,  $nFname);

                $image = new Imagick($nFname);
                $image->thumbnailimage($config->logo->width,$config->logo->width,true);
                $image->setimageformat('jpg');
                $image->writeimage($basePath .'/'. basename($nFname));

            }

            $logo->setID($id)
                ->setURL($this->_getParam('url'))
                ->setLogo(basename($nFname));

            $validator = new Infinite_Logo_UpdateValidator();
            if ($validator->validate($logo)) {
                $logo->save();

                $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                $flashMessenger->addMessage('De gegevens werden succesvol aangepast!');

                $this->_helper->redirector->gotoSimple('index', 'logo');
            }
        }

        $this->view->item  = $logo;
        $this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Logo');
    }

    public function deleteAction()
    {
        $config = Zend_Registry::get('config');
        $id = $this->_getParam('id');
        $mapper = new Infinite_Logo_DataMapper();
        $logo = $mapper->getById($id);
        $logo->delete();

        $image = APPLICATION_ROOT . '/'.$config->public->folder.'/img/logo/' . $logo->getLogo();
        if (file_exists($image)) { unlink($image); }

        $flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $flashMessenger->addMessage('Het logo werd succesvol verwijderd!');

        $this->_helper->redirector->gotoSimple('index', 'logo');
    }


}
