<?php

class Infinite_GroupAcl_DataMapper
{
	public function getByGroupID($groupID)
	{
		$db = Zend_Registry::get('db');
		$select = $db->select()
			->from('GroupAcl', array('*'))
			->where('groupID = ?', (int) $groupID);

		$stmt = $db->query($select);
		$results = $stmt->fetchAll();

        $acls = array();
        foreach($results as $result) {
            $acl = new Infinite_GroupAcl();
            $acl->makeObject($result);
            $acls[$acl->getId()] = $acl;
        }

		return $acls;
	}

}
