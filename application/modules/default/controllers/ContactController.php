<?php

require_once 'Zend/Controller/Action.php';

class ContactController extends Zend_Controller_Action
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

        $this->view->contact_tmx = new Zend_Translate(
            array(
                'adapter' => 'tmx',
                'content' => APPLICATION_ROOT . '/application/configs/contact.tmx',
                'locale'  => $system->lng
            )
        );

    }

    public function formAction()
    {
        $config = Zend_Registry::get('config');
        $this->_helper->layout->setLayout('contact');

		$lang = $this->_getParam('lng', 'nl');
		$mapper = new Infinite_Content_DataMapper();
		$page = $mapper->getByUrl('contact', $lang);
		$this->view->page = $page;

        if ($this->getRequest()->isGet()) {
            $contact = new Infinite_Contact();
            $this->view->contact   = $contact;
        }

        if ($this->getRequest()->isPost()) {
            $link = $this->getRequest()->getRequestUri();
            $contact = new Infinite_Contact();

            //get verify response data
            //$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$config->recaptcha->secret.'&response='.$this->_getParam('g-recaptcha-response'));
            //$responseData = json_decode($verifyResponse);

            $contact
                ->setDatetime(date('Y-m-d H:i:s', time()))
                ->setLng($this->_getParam('lng'))
                ->setName($this->_getParam('name'))
                ->setEmail($this->_getParam('email'))
                ->setPhone($this->_getParam('phone'))
                ->setMessage($this->_getParam('message'));

            $validator = new Infinite_Contact_Validator();
            if ($validator->validate($contact)) {
            //if ($validator->validate($contact) && $responseData->success) {

                $mail = new Axento_Mail();
                $mail->sendForm($contact);

                $contact->save();
                $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                $flashMessenger->addMessage("<b>Bedankt voor uw interesse!</b><br />Wij hebben uw bericht succesvol ontvangen en zullen zo snel mogelijk contact met u opnemen.");
                $this->_helper->_redirector->gotoUrl($link);
            }

        }

        $this->view->sitekey = $config->recaptcha->sitekey;

        $this->view->messages = Infinite_ErrorStack::getInstance('Infinite_Contact');
        $this->view->contact = $contact;
    }

}
