<?php

class Infinite_Account_DataMapper
{

	public function getById($id)
	{
		$db = Zend_Registry::get('db');
		$select = $db->select()
			->from(array('a' => 'Account'), array('*'))
			->where('a.id = ' . (int) $id);

		$result = $db->fetchRow($select);
        $account = new Infinite_Account();
        $account->makeObject($result);
		return $account;
	}
    
    public function getByEmail($email)
	{
		$db = Zend_Registry::get('db');
		$select = $db->select()
			->from(array('a' => 'Account'), array('*'))
			->where("a.email = '$email'");

		$result = $db->fetchRow($select);
		if ($result) {
            $account = new Infinite_Account();
            $account->makeObject($result);
            return $account;
        } else {
            return false;
        }
	}
    
	public function getByAccount($email)
	{
		$db = Zend_Registry::get('db');
		$select = $db->select()
			->from(array('a' => 'Account'), array('*'))
            ->where('a.email LIKE "' . $email . '"');

		$result = $db->fetchRow($select);
        $account = new Infinite_Account();
        $account->makeObject($result);

		return $account;
	}

	public function getAllAccounts()
	{
		$db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('a' => 'Account'), array('*'))
            ->order('a.fname')
            ->order('a.name');

        $results = $db->fetchAll($select);

        $accounts = array();
        foreach($results as $result) {
            $account = new Infinite_Account();
            $account->makeObject($result);

            $select = $db->select()
                ->from(array('g' => 'Group'), array('*'))
                ->where('g.id = ?',$account->getGroupID());
            $res = $db->fetchRow($select);
            $group = new Infinite_Group();
            $group->setId($res['id'])
                ->setName($res['name']);
            $account->addGroup($group);

            $accounts[$account->getId()] = $account;

        }

        return $accounts;
	}

}
