<?php

class GalleryController extends Zend_Controller_Action
{


	public function homepageAction()
	{

        /* controleren of dit een actief album is */
        $mapper = new Smart_Gallery_DataMapper();
        $gallery = $mapper->getById(1);

        if ($gallery->getActive() == 1) {
            $mapper = new Smart_Gallery_Picture_DataMapper();
            $pics[] = $mapper->getAllByAlbum(1, 'nl');
        }

        $this->view->pics = $pics;

	}

}