<?php

class Infinite_ContentAcl_DataMapper
{

	public function getByContentID($contentID)
	{
		$db = Zend_Registry::get('db');
		$select = $db->select()
			->from(array('ca' => 'ContentAcl'), array('*'))
            ->join(array('g' => 'Group'), 'ca.groupID = g.id', array('name'))
			->where('contentID = ?', (int) $contentID);

		$stmt = $db->query($select);
		$results = $stmt->fetchAll();

        $acls = array();
        foreach($results as $result) {
            $acl = new Infinite_ContentAcl();
            $acl->makeObject($result);
            $acls[] = $acl;
        }
		return $acls;
	}

    public function getByGroupID($groupID)
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from('ContentAcl', array('*'))
            ->where('groupID = ?', (int) $groupID);

        $stmt = $db->query($select);
        $results = $stmt->fetchAll();

        $acls = array();
        foreach($results as $result) {
            $acl = new Infinite_ContentAcl();
            $acl->makeObject($result);
            $acls[$acl->getId()] = $acl;
        }

        return $acls;
    }

}
