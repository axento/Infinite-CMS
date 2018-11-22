<?php

class Infinite_Club_DataMapper
{

	public function getById($id)
	{

        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('m' => 'Club'), array('*'))
            ->where('m.id = ?', $id);

        $result = $db->fetchRow($select);
        if ($result) {
            $club = new Infinite_Club();
            $club->makeObject($result);
            return $club;
        } else {
            return false;
        }
	}

    public function getByURL($url)
    {

        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('m' => 'Club'), array('*'))
            ->where('m.url = ?', $url);

        $result = $db->fetchRow($select);
        if ($result) {
            $club = new Infinite_Club();
            $club->makeObject($result);
            return $club;
        } else {
            return false;
        }
    }

	public function getAllClubs()
	{
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('m' => 'Club'), array('*'))
            ->order('m.company ASC');

        $stmt = $db->query($select);
        $results = $stmt->fetchAll();

        $clubs = array();
        foreach($results as $result) {
            $club = new Infinite_Club();
            $club->makeObject($result);
			$clubs[$club->getID()] = $club;
        }

        return $clubs;
	}
        
}
