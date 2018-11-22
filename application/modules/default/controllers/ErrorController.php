<?php

class ErrorController extends Zend_Controller_Action
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

    public function errorAction()
    {

        $this->_helper->layout->setLayout('error');

        $errors = $this->_getParam('error_handler');
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->render('404');
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                break;
        }

    }

    public function forbiddenAction()
    {
        $this->getResponse()->setHttpResponseCode(401);
        $this->_helper->layout->setLayout('error');
    }
}
