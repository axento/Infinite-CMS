<?php

class Infinite_Logo_DataMapper
{

	public function getById($id)
	{

        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('m' => 'Logo'), array('*'))
            ->where('m.id = ?', $id);

        $result = $db->fetchRow($select);
        if ($result) {
            $logo = new Infinite_Logo();
            $logo->makeObject($result);
            return $logo;
        } else {
            return false;
        }
	}

	public function getAll()
	{
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('m' => 'Logo'), array('*'))
            ->order('m.id ASC');

        $stmt = $db->query($select);
        $results = $stmt->fetchAll();

        $logos = array();
        foreach($results as $result) {
            $logo = new Infinite_Logo();
            $logo->makeObject($result);
			$logos[$logo->getID()] = $logo;
        }

        return $logos;
	}

    public function getActive()
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('m' => 'Logo'), array('*'))
            ->where('m.active = 1')
            ->order('m.id ASC');

        $stmt = $db->query($select);
        $results = $stmt->fetchAll();

        $logos = array();
        foreach($results as $result) {
            $logo = new Infinite_Logo();
            $logo->makeObject($result);
            $logos[$logo->getID()] = $logo;
        }

        return $logos;
    }
        
}
