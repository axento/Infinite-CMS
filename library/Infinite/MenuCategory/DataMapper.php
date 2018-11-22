<?php

class Smart_MenuCategory_DataMapper
{

    public function getAll()
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('mc' => 'MenuCategory'), array('*'))
            ->joinLeft(array('m' => 'Menu'), 'm.id = mc.menuID', array('menuTitle' => 'title', 'menuContent' => 'content'))
            ->order('mc.menuID ASC')
            ->order('mc.ranking ASC');

        $stmt = $db->query($select);
        $results = $stmt->fetchAll();

        $categories = array();
        foreach($results as $result) {
            $category = new Infinite_MenuCategory();
            $category->makeObject($result);
            $categories[$category->getID()] = $category;
        }

        return $categories;
    }

	public function getById($id)
	{
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('mc' => 'MenuCategory'), array('*'))
            ->where('mc.id = ?', $id);

        $result = $db->fetchRow($select);
        if ($result) {
            $menuCategory = new Infinite_MenuCategory();
            $menuCategory->makeObject($result);
            return $menuCategory;
        } else {
            return false;
        }
	}

    public function getByMenuID($menuID)
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(array('mc' => 'MenuCategory'), array('*'))
            ->where('mc.menuID = ?', $menuID)
            ->order('mc.ranking ASC');

        $stmt = $db->query($select);
        $results = $stmt->fetchAll();

        $categories = array();
        foreach($results as $result) {
            $category = new Infinite_MenuCategory();
            $category->makeObject($result);
            $categories[$category->getID()] = $category;
        }

        return $categories;
    }

}
