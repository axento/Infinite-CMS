<?php

class Infinite_Menu_DataMapper
{

	public function getByID($id)
	{

        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('b' => 'Menu'), array('*'))
            ->where('b.id = ?', $id);

        $result = $db->fetchRow($select);
        if ($result) {
            $menu = new Infinite_Menu();
            $menu->makeObject($result);
            return $menu;
        } else {
            return false;
        }
	}

	public function getAll()
	{
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('b' => 'Menu'), array('*'))
            ->order('b.title ASC');

        $stmt = $db->query($select);
        $results = $stmt->fetchAll();

        $menus = array();
        foreach($results as $result) {
            $menu = new Infinite_Menu();
            $menu->makeObject($result);
			$menus[$menu->getID()] = $menu;
        }

        return $menus;
	}
        
}
