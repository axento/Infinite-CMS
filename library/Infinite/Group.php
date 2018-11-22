<?php

class Infinite_Group
{

	protected $id;
	protected $name;

    protected $acls = array();
    
	public function setId($id)
	{
		$this->id = (int) $id;
		return $this;
	}
	public function getId()
	{
		return $this->id;
	}

	public function setName($name)
	{
		$this->name = trim($name);
		return $this;
	}
	public function getName()
	{
		return $this->name;
	}

    public function addAcl(Infinite_Acl $acl)
    {
        $this->acls[$acl->getId()] = $acl;
        return $this;
    }
    public function getAcls()
    {
        return $this->acls;
    }

    public function makeObject($result) {
        $this->setId($result['id'])
            ->setName($result['name']);

        return $this;
    }

    public function save(Infinite_Group $group)
	{
		$db = Zend_Registry::get('db');
		$data = array(
			'name' => $group->getName(),
            'dateUpdated' => date("Y-m-d H:i:s", time())
		);

		if ($group->getId()) {
			$data['dateUpdated'] = new Zend_Db_Expr('CURDATE()');
			$db->update('Group', $data, 'id = ' . $group->getId());

			return true;
		}

		$data['dateCreated'] = new Zend_Db_Expr('CURDATE()');
		$db->insert('Group', $data);
		$group->setId($db->lastInsertId());

		return true;
	}
    
    public function delete(Infinite_Group $group)
	{
		$db = Zend_Registry::get('db');
        $db->delete('GroupAcl', 'groupID = ' . $group->getId());
		return $db->delete('Group', 'id = ' . $group->getId());
	}

}
