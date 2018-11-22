<?php

class Infinite_GroupAcl
{

    protected $id;
	protected $groupID;
    protected $aclID;

    public function setID($id)
    {
        $this->id = (int) $id;
        return $this;
    }
    public function getID()
    {
        return $this->id;
    }

	public function setgroupID($groupID)
	{
		$this->groupID = (int) $groupID;
		return $this;
	}
	public function getGroupID()
	{
		return $this->groupID;
	}

    public function setAclID($aclID)
    {
        $this->aclID = (int) $aclID;
        return $this;
    }
    public function getAclID()
    {
        return $this->aclID;
    }

    public function makeObject($result) {
        $this->setID($result['id'])
            ->setgroupID($result['groupID'])
            ->setAclID($result['aclID']);

        return $this;
    }
    
    public function save()
	{
		$db = Zend_Registry::get('db');
		$data = array(
			'groupID' => $this->getGroupID(),
            'aclID' => $this->getAclID()
		);

		$db->insert('GroupAcl', $data);
		return true;
	}
    
    public function delete()
	{
		$db = Zend_Registry::get('db');
        $db->delete('GroupAcl', 'aclID = ' . $this->getAclID());
		return true;
	}

    public function clean()
    {
        $db = Zend_Registry::get('db');
        $db->delete('GroupAcl', 'groupID = ' . $this->getGroupID());
        return true;
    }
}
