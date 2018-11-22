<?php

class Infinite_Account
{

	protected $id = null;
    protected $groupID;
	protected $active = 0;
	protected $email = '';
	protected $password = '';
	protected $fname = '';
	protected $name = '';

    protected $groups = array();

	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}
	public function getId()
	{
		return $this->id;
	}

    public function setGroupID($groupID)
    {
        $this->groupID = $groupID;
        return $this;
    }
    public function getGroupID()
    {
        return $this->groupID;
    }

	public function setEmail($email)
	{
		$this->email = strtolower(trim($email));
		return $this;
	}
	public function getEmail()
	{
		return $this->email;
	}

	public function setPassword($password)
	{
		$this->password = (string) $password;
		return $this;
	}
	public function getPassword()
	{
		return $this->password;
	}

	public function setFname($fname)
	{
		$this->fname = (string) $fname;
		return $this;
	}
	public function getFname()
	{
		return $this->fname;
	}

	public function setName($name)
	{
		$this->name = (string) $name;
		return $this;
	}
	public function getName()
	{
		return $this->name;
	}

	public function getFullName()
	{
		return $this->getFname() . ' ' . $this->getName();
	}

	public function setActive($active = true)
	{
		$this->active = (bool) $active;
		return $this;
	}
	public function isActive()
	{
		return $this->active === true;
	}

    public function addGroup(Infinite_Group $group)
    {
        $this->groups[$group->getId()] = $group;
        return $this;
    }
    public function getGroups()
    {
        return $this->groups;
    }

    public function makeObject($result) {
        $this->setID($result['id'])
            ->setGroupID($result['groupID'])
            ->setActive($result['active'])
            ->setEmail($result['email'])
            ->setFname($result['fname'])
            ->setName($result['name']);

        return $this;
    }

	public function login()
	{
		$dbAdapter = Zend_Registry::get('db');
		$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
		$authAdapter
		    ->setTableName('Account')
		    ->setIdentityColumn('email')
		    ->setCredentialColumn('password')
		    ->setCredentialTreatment('SHA1(?)');

		$authAdapter
		    ->setIdentity($this->getEmail())
		    ->setCredential($this->password);

		$auth = Zend_Auth::getInstance();
		$result = $auth->authenticate($authAdapter);
		if (!$result->isValid()) {
			return false;
		}

	    $account = $authAdapter->getResultRowObject(null, 'password');
        if ($account && !$account->active) {
            $msg = Infinite_ErrorStack::getInstance('Infinite_Account');
            $msg->addMessage('email', array("email" => "Deze gebruiker is niet actief"));
            $auth->clearIdentity();

            return false;
        }

		$mapper = new Infinite_Account_DataMapper();
        $account = $mapper->getById($account->id);

	    $storage = $auth->getStorage();
	    $storage->write($account);

	    return true;
	}

	public function save()
	{
		$db = Zend_Registry::get('db');
        $account = array(
			'groupID' => $this->groupID,
            'email' 	 => $this->email,
			'active'     => $this->active,
			'fname' => $this->fname,
			'name'  => $this->name
		);

		if ($this->password) {
            $account['password'] = sha1($this->password);
		}

		if ($this->id !== null) {
            $account['dateUpdated'] = new Zend_Db_Expr('CURDATE()');
			$db->update('Account', $account, 'id = ' . $this->id);
		} else {
            $account['dateCreated'] = new Zend_Db_Expr('CURDATE()');
			$db->insert('Account', $account);
			$this->setId($db->lastInsertId());
		}
        
		return true;
	}
    
	public function update($email)
	{

        $db = Zend_Registry::get('db');
        $account = array(
            'email' 	 => $this->email,
			'fname' => $this->fname,
			'name'  => $this->name
		);

        if ($this->password) {
            $account['password'] = sha1($this->password);
        }

		$db->update('Account', $account, 'email LIKE "' . $email . '"');
        
		return true;
	}
    
    public function delete(Infinite_Account $account)
	{
		$db = Zend_Registry::get('db');
		return $db->delete('Account', 'id = ' . $account->getId());
	}
    
	public function activate()
	{
		$db = Zend_Registry::get('db');
        $account = array('active'    => ((- $this->active)  + 1) );

		$db->update('Account', $account, 'id = ' . $this->id);

		return true;
	}
    
	public function restorePassword($id,$password)
	{
		$db = Zend_Registry::get('db');
        $account = array('password'    => $password);

		$db->update('Account', $account, 'id = ' . $this->id);

		return true;
	}
}
