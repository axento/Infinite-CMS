<?php

class Infinite_Acl_DataMapper
{
	public function getAll()
	{
		$db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('a' => 'Acl'), array('*'));

        $results = $db->fetchAll($select);
        
        $acls = array();
        foreach($results as $result) {
            $acl = new Infinite_Acl();
            $acl->makeObject($result);
            $acls[$acl->getID()] = $acl;
        }
        return $acls;
	}

    public function getByID($id)
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('a' => 'Acl'), array('*'))
            ->where('a.id = ' . $id);

        $stmt = $db->query($select);
        $result = $stmt->fetch();

        $acl = new Infinite_Acl();
        $acl->makeObject($result);
        return $acl;
    }
        
}