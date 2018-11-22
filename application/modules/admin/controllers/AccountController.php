<?php

require_once('Axento/Password.php');
class Admin_AccountController extends Zend_Controller_Action
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

        $this->_instance = 'Infinite_Account';
        $this->view->accountTab = 'active';
    }

	public function indexAction()
	{
        Infinite_Acl::checkAcl('account');

        $mapper = new Infinite_Account_DataMapper();
        $accounts = $mapper->getAllAccounts();
        $this->view->accounts = $accounts;
	}

    public function addAction()
    {
        Infinite_Acl::checkAcl('account');

    	$mapper = new Infinite_Group_DataMapper();
    	$this->view->groups = $mapper->getAll();

		$account = new Infinite_Account();
        $account->setGroupID(1);

		if ($this->getRequest()->isPost()) {
			$account->setEmail($this->_getParam('email'))
				 ->setFname($this->_getParam('fname'))
				 ->setName($this->_getParam('name'))
                 ->setPassword(Zend_Registry::get('config')->encryption->salt . $this->_getParam('password'))
                 ->setGroupID($this->_getParam('group'));

			$validator = new Infinite_Account_InsertValidator();
            $validator->setPasswordRepeat(Zend_Registry::get('config')->encryption->salt . $this->_getParam('password_repeat'));

            if ($validator->validate($account)) {
                $account->save();

                $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                $flashMessenger->addMessage('De gebruiker werd succesvol aangemaakt!');

				$this->_helper->redirector->gotoSimple('index', 'account');
			}
		}

		$this->view->account = $account;
		$this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Account');
    }

    public function editAction()
    {
        Infinite_Acl::checkAcl('account');

    	$id = (int) $this->_getParam('id');

    	$mapper = new Infinite_Group_DataMapper();
    	$this->view->groups = $mapper->getAll();

    	$mapper = new Infinite_Account_DataMapper();
    	$account  = $mapper->getById($id);

        if ($this->getRequest()->isPost()) {
            $account->setID($id)
                 ->setFname($this->_getParam('fname'))
                 ->setName($this->_getParam('name'))
                 ->setEmail($this->_getParam('email'))
                 ->setGroupID($this->_getParam('group'));

            if ($this->_getParam('password') || $this->_getParam('password_repeat')) {
                 $account->setPassword(Zend_Registry::get('config')->encryption->salt . $this->_getParam('password'));
            }

            $validator = new Infinite_Account_UpdateValidator();
            $validator->setOldPassword(Zend_Registry::get('config')->encryption->salt . $this->_getParam('old_password'));
            $validator->setPasswordRepeat(Zend_Registry::get('config')->encryption->salt . $this->_getParam('password_repeat'));
            if ($validator->validate($account)) {
                $account->save();

                $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                $flashMessenger->addMessage('De gegevens werden succesvol aangepast!');

                $this->_helper->redirector->gotoSimple('index', 'account');
            }
        }

        $this->view->account = $account;
        $this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Account');
    }

    public function activateAction()
    {
        Infinite_Acl::checkAcl('account');
    	$id = $this->_getParam('id');

    	$mapper = new Infinite_Account_DataMapper();
    	$account = $mapper->getById($id);
    	$account->activate();

    	$this->_helper->redirector->gotoSimple('index', 'account');
    }

    public function deleteAction()
    {
        Infinite_Acl::checkAcl('account');
    	$account = new Infinite_Account();
    	$account->setId($this->_getParam('id'));
		$account->delete($account);

		$flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$flashMessenger->addMessage('Gebruiker werd succesvol verwijderd!');

		$this->_helper->redirector->gotoSimple('index', 'account');
    }

    public function logoutAction()
    {
        $account = Zend_Auth::getInstance()->getIdentity();
        $usr = array(
            'user' => $account->getEmail(),
            'password' => $account->getPassword()
        );
        $cookieName = Zend_Registry::get('config')->cookie->name;
        setcookie($cookieName,urlencode(serialize($usr)),time()-(60*60*24*365),'/',Zend_Registry::get('config')->system->web->domain);
        Zend_Auth::getInstance()->clearIdentity();
    	$this->_helper->redirector->gotoSimple('index', 'index');
    }

	public function loginAction()
	{
        /* Indien de gebruiker de 'Remember Me' functie gebruikt heeft, onmiddelijk aanmelden */
        if (isset($_COOKIE[Zend_Registry::get('config')->cookie->name])) {
            $rememberUser = unserialize(urldecode($_COOKIE[Zend_Registry::get('config')->cookie->name]));
            $account = new Infinite_Account();
            $account   ->setEmail($rememberUser['user'])
                    ->setPassword($rememberUser['password']);

            $validator = new Infinite_Account_LoginValidator();
            if ($validator->validate($account) && $account->login()) {
                $system = new Zend_Session_Namespace('System');
                $system->lng = 'nl';

                $mapper = new Infinite_Account_DataMapper();
                $usr = $mapper->getByAccount($account->getEmail());

                $accountNamespace = new Zend_Session_Namespace('userNamespace');
                $accountNamespace->fname = $usr->getFname();
                $accountNamespace->name = $usr->getName();
                $accountNamespace->email = $usr->getEmail();
                $accountNamespace->isLoggedIn = true;
                $_SESSION['tinymceLoggedIn'] = true;

                $this->_helper->redirector->gotoSimple('index', 'index');
            }
        }

        $account = new Infinite_Account();
		if ($this->getRequest()->isPost()) {
			if ($this->_getParam('login')) {
     
                $account->setEmail(strip_tags($this->_getParam('username')))
                     ->setPassword(Zend_Registry::get('config')->encryption->salt . strip_tags($this->_getParam('password')) );

    			$validator = new Infinite_Account_LoginValidator();
    			if ($validator->validate($account) && $account->login()) {
    				$system = new Zend_Session_Namespace('System');
                    $system->lng = 'nl';
                    
                    $mapper = new Infinite_Account_DataMapper();
                    $usr = $mapper->getByAccount(strip_tags($this->_getParam('username')));

                    $accountNamespace = new Zend_Session_Namespace('userNamespace');
                    $accountNamespace->fname = $usr->getFname();
                    $accountNamespace->name = $usr->getName();
                    $accountNamespace->email = $usr->getEmail();
                    $accountNamespace->isLoggedIn = true;
                    $_SESSION['tinymceLoggedIn'] = true;

                    if ($this->_getParam('rememberMe') == '1') {
                        $rememberUser = array(
                            'user' => $account->getEmail(),
                            'password' => $account->getPassword()
                        );
                        setcookie(Zend_Registry::get('config')->cookie->name,urlencode(serialize($rememberUser)),time()+(60*60*24*365),'/',Zend_Registry::get('config')->system->web->domain);
                    }

    				$this->_helper->redirector->gotoSimple('dashboard', 'index');
                }
            }
            if ($this->_getParam('forgot')) {
                $mapper = new Infinite_Account_DataMapper();
                $forgotten = $mapper->getByEmail(strip_tags($this->_getParam('email')));
                
                if ($forgotten && $forgotten->getEmail()) {
                    // Restore new password in database
                    $password = generatePassword();
                    $sha1 = sha1(Zend_Registry::get('config')->encryption->salt . $password);
                    $member = new Infinite_Account();
                    $member ->setId($forgotten->getId())
                            ->setPassword($sha1);
                    $member->restorePassword($forgotten->getId(),$sha1);

                    // Send new password by e-mail to user
                    $mail = new Axento_Mail();
                    $name = $forgotten->getFname().' '.$forgotten->getName();
                    $mail->sendNewPassword($forgotten->getEmail(),$password,$name);

                    $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                    $flashMessenger->addMessage('Uw nieuw paswoord werd verzonden naar '.$this->_getParam('email'));
                    $this->_helper->redirector->gotoSimple('login', 'account');
    
                } else {
                    //no user found
                    $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                    $flashMessenger->addMessage('Er werd geen gebruiker gevonden met dit e-mailadres!');
                    $this->_helper->redirector->gotoSimple('login', 'account');
                }
    
            }
		}

		$this->view->account = $account;
		$this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Account');

		if (!$this->getRequest()->getParam('redirect')) {
			$this->_helper->layout->setLayout('login');
		}
	}

	public function profileAction()
    {
        Infinite_Acl::checkAcl('account');
        $accountNamespace = new Zend_Session_Namespace('userNamespace');
        //Zend_Debug::dump($accountNamespace->email);

        //$id = (int) $this->_getParam('id');

        $mapper = new Infinite_Account_DataMapper();
        $account  = $mapper->getByAccount($accountNamespace->email);

        if ($this->getRequest()->isPost()) {
            $account
                ->setFname($this->_getParam('fname'))
                ->setName($this->_getParam('name'))
                ->setEmail($this->_getParam('email'));

            if ($this->_getParam('password') || $this->_getParam('password_repeat')) {
                $account->setPassword(Zend_Registry::get('config')->encryption->salt . $this->_getParam('password'));
            }

            $validator = new Infinite_Account_UpdateValidator();
            $validator->setOldPassword(Zend_Registry::get('config')->encryption->salt . $this->_getParam('old_password'));
            $validator->setPasswordRepeat(Zend_Registry::get('config')->encryption->salt . $this->_getParam('password_repeat'));
            if ($validator->validate($account)) {
                $account->update();

                $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                $flashMessenger->addMessage('Het profiel werd succesvol aangepast!');

                $this->_helper->redirector->gotoSimple('profile', 'account');
            }
        }

        $this->view->account = $account;
        $this->view->errors = Infinite_ErrorStack::getInstance('Infinite_Account');
    }
}
