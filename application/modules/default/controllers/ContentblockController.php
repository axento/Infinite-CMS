<?php

class ContentblockController extends Zend_Controller_Action
{
	 public function init()
    {
        $config = Zend_Registry::get('config');
        $system = new Zend_Session_Namespace('system');
        $system->lng = $this->_getParam('lng', $config->system->defaults->language);
        $this->view->lng = $system->lng;

        $this->view->global_tmx = new Zend_Translate(
            array(
                'adapter' => 'tmx',
                'content' => APPLICATION_ROOT . '/application/configs/global.tmx',
                'locale'  => $system->lng
            )
        );
    }


    public function viewAction() {
        $name = $this->_getParam('name');
        $mapper = new Smart_Contentblock_DataMapper();
        $block = $mapper->getByBlockname($name, $_SESSION['System']['lng']);

        $this->view->block = $block;
    }

    
}