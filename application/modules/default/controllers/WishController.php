<?php

require_once 'Zend/Controller/Action.php';

class WishController extends Zend_Controller_Action
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

    public function indexAction()
    {
        $mapper = new Infinite_Wish_DataMapper();
        $wishes = $mapper->getActive();
        $this->view->wishes = $wishes;
        //Zend_Debug::dump($wishes);
    }

    public function formAction()
    {
        $config = Zend_Registry::get('config');
        $this->_helper->layout->setLayout('contact');

		$lang = $this->_getParam('lng', 'nl');
		$mapper = new Infinite_Content_DataMapper();
		$page = $mapper->getByUrl('wish', $lang);
		$this->view->page = $page;

		$ip_class = new Axento_IP();

        if ($this->getRequest()->isGet()) {
            $wish = new Infinite_Wish();
            $this->view->wish   = $wish;
        }

        if ($this->getRequest()->isPost()) {
            $link = $this->getRequest()->getRequestUri();
            $wish = new Infinite_Wish();

            //get verify response data
            //$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$config->recaptcha->secret.'&response='.$this->_getParam('g-recaptcha-response'));
            //$responseData = json_decode($verifyResponse);

            $wish->setActive(0)
                ->setDateCreated(date('Y-m-d H:i:s', time()))
                ->setFromName($this->_getParam('fromName'))
                ->setFromFname($this->_getParam('fromFname'))
                ->setToName($this->_getParam('toName'))
                ->setToFname($this->_getParam('toFname'))
                ->setWish($this->_getParam('wish'))
                ->setIP($ip_class->getUserIP());

            $validator = new Infinite_Wish_Validator();
            if ($validator->validate($wish)) {
            //if ($validator->validate($wish) && $responseData->success) {

                $mail = new Axento_Mail();
                $mail->sendWish($wish);

                $wish->save();
                $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                $flashMessenger->addMessage("<b>Bedankt voor uw ingave!</b><br />Uw bericht wordt geplaatst na controle door de redactie..");
                //$this->_helper->_redirector->gotoUrl($link);
                $this->_helper->redirector->gotoSimple('index', 'wish');
            }

        }

        $this->view->sitekey = $config->recaptcha->sitekey;

        $this->view->messages = Infinite_ErrorStack::getInstance('Infinite_Wish');
        $this->view->wish = $wish;
        $this->view->ip = $ip_class->getUserIP();
    }

}
