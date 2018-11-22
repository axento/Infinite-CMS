<?php

class Admin_Gallery_PictureController extends Zend_Controller_Action
{
	protected $_album;

	public function init()
	{
        $this->view ->placeholder('modules')
                    ->append('active');
        $this->view->galleryTab = 'active';
		$config = Zend_Registry::get('config');
		$lngs = $config->system->language;
		$this->view->lngs = $lngs;
	}

    public function browseAction()
	{
        /* Config */
        $config = Zend_Registry::get('config');
        $this->view->width = $config->image->large->width;
        $this->view->height = $config->image->large->height;
        $this->view->filesize = $config->image->filesize; // Max image size


        $album_id	= $this->_getParam('id');
        $this->view->album_id = $album_id;
        
        $mapper = new Infinite_Gallery_DataMapper();
        $album = $mapper->getById($album_id);
        $this->view->album = $album;
        
        if ($this->getRequest()->isPost()) {

            $uploadPath		= APPLICATION_ROOT . '/'.$config->public->folder.'/img/gallery';

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

            $basePath = APPLICATION_ROOT . '/'.$config->public->folder.'/img/gallery/album_' . $album_id;
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

                // Set ranking
                $mapper = new Infinite_Gallery_Picture_DataMapper();
                $highestRank = $mapper->getHighestRank($album_id);
                if ($highestRank) {
                    $ranking = (int) $highestRank->getRanking() + 1;
                } else {
                    $ranking = 1;
                }


                $picture = new Infinite_Gallery_Picture();
                $picture->setGid($album_id);
                $picture->setRanking($ranking);
                $picture->setImage(basename($nFname));
                $picture->setTitle($this->_getParam('title'));
                $picture->setSubtitle($this->_getParam('subtitle'));
                $picture->save();

                $original = APPLICATION_ROOT . '/'.$config->public->folder.'/img/gallery/album_' . $album_id . '/' . $picture->getImage();
                if (file_exists($original)) { unlink($original); }
            }

      }
                
        $mapper =  new Infinite_Gallery_Picture_DataMapper();
        $pictures = $mapper->getAllByAlbum($album_id);
        $this->view->pictures = $pictures;

	}

    public function editAction() {

        $picture_id = $this->_getParam('id');
        $mapper = new Infinite_Gallery_Picture_DataMapper();
        $picture = $mapper->getByID($picture_id);

        if ($this->getRequest()->isPost())
        {
            $picture->setTitle($this->_getParam('title'));
            $picture->setSubtitle($this->_getParam('subtitle'));
            $picture->save();

            $flashMessenger = $this->_helper->getHelper('FlashMessenger');
            $flashMessenger->addMessage('De gegevens werden succesvol aangepast!');

            $this->getHelper('Redirector')->gotoSimple('browse', 'gallery_picture', 'admin', array('id' => $picture->getGid()));

        }

        $this->view->picture = $picture;
        $this->view->picture_id = $picture_id;
    }

    public function deleteAction()
    {
        $config = Zend_Registry::get('config');
        $picture_id = $this->_getParam('id');
        $mapper = new Infinite_Gallery_Picture_DataMapper();
        $picture = $mapper->getByID($picture_id);
        $albumID = $picture->getGid();
        $picture->delete();

        $original = APPLICATION_ROOT . '/'.$config->public->folder.'/img/gallery/album_' . $albumID . '/' . $picture->getImage();
        $thumb = APPLICATION_ROOT . '/'.$config->public->folder.'/img/gallery/album_' . $albumID . '/small/' . $picture->getImage();
        $image = APPLICATION_ROOT . '/'.$config->public->folder.'/img/gallery/album_' . $albumID . '/large/' . $picture->getImage();

        if (file_exists($original)) { unlink($original); }
        if (file_exists($thumb)) { unlink($thumb); }
        if (file_exists($image)) { unlink($image); }

        $flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $flashMessenger->addMessage('De afbeelding werd succesvol verwijderd!');

        $this->getHelper('Redirector')->gotoSimple('browse', 'gallery_picture', 'admin', array('id' => $picture->getGid()));
    }

    public function setOrderAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $sort_order = $this->_getParam('sort_order');
        $ranking = explode(',',$sort_order);

        foreach($ranking as $key => $pictureID) {

            $picture = new Infinite_Gallery_Picture();
            $picture->setPictureId($pictureID)
                ->setRanking($key+1);
            $picture->rank();
        }

    }

}
