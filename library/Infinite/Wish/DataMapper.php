<?php

class Infinite_Wish_DataMapper
{

    public function getByID($id)
    {

        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('m' => 'Wish'), array('*'))
            ->where('m.id = ?', $id);

        $result = $db->fetchRow($select);
        if ($result) {
            $wish = new Infinite_Wish();
            $wish->makeObject($result);
            return $wish;
        } else {
            return false;
        }
    }

    public function getAll()
	{
		$db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('c' => 'Wish'), array('*'))
            ->order('c.dateCreated DESC');

        $results = $db->fetchAll($select);
        
        $wishes = array();
        foreach($results as $result) {
            $wish = new Infinite_Wish();
            $wish->makeObject($result);
            $wishes[$wish->getID()] = $wish;
        }
        return $wishes;
	}

    public function getActive()
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('c' => 'Wish'), array('*'))
            ->where('c.active = 1')
            ->order('c.dateCreated DESC');

        $results = $db->fetchAll($select);

        $wishes = array();
        foreach($results as $result) {
            $wish = new Infinite_Wish();
            $wish->makeObject($result);
            $wishes[$wish->getID()] = $wish;
        }
        return $wishes;
    }
        
}