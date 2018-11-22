<?php

class Infinite_Group_DataMapper
{
    /*
    public function toObject(Array $import)
    {
        $fields = array(
            'id'   => null,
            'name'       => null
        );

        foreach ($import as $key => $value) {
            if (array_key_exists($key, $fields)) {
                $fields[$key] = $value;
            }
        }

		$group = new Infinite_Group();
		$group->setId($fields['id'])
			  ->setName($fields['name']);

        return $group;
    }
    */
	public function getById($id)
	{
		$db = Zend_Registry::get('db');
		$select = $db->select()
			->from('Group', array('*'))
			->where('id = ?', (int) $id);

		$stmt = $db->query($select);
		$result = $stmt->fetch();

        $group = new Infinite_Group();
        $group->makeObject($result);
		return $group;
	}

	public function getAll()
	{
		$db = Zend_Registry::get('db');
		$select = $db->select()
			->from('Group', array('*'))
			->order('name');

		$stmt = $db->query($select);
		$rows = $stmt->fetchAll();

		$groups = array();
		foreach ($rows as $result) {
            $group = new Infinite_Group();
            $group->makeObject($result);
			$groups[$group->getId()] = $group;

            $db = Zend_Registry::get('db');
            $select = $db->select()
                ->from(array('ga' => 'GroupAcl'), array('*'))
                ->join(array('a' => 'Acl'), 'a.id = ga.aclID', array('id', 'name'))
                ->where('ga.groupID = ' . $group->getId());

            $result = $db->fetchAll($select);
            foreach ($result as $row) {
                $acl = new Infinite_Acl();
                $acl->setId($row['id'])
                    ->setName($row['name']);

                $group->addAcl($acl);
            }

		}

		return $groups;
	}

}
